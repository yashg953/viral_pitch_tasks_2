/**
    * Created by Prashant on 14-07-2019.
    * https://github.com/psachan190
    * https://www.linkedin.com/in/psachan190
    * E-mail: psachan190@gmail.com
    
    
    * Abhishek Verma
    * https://github.com/abhinittkkr
    * https://linkedin.com/in/abhinittkkr
**/

var express         = require('express');
var path            = require('path');
var app             = express();
var server          = require('http').Server(app);
var io              = require('socket.io')(server);
var bodyParser      = require('body-parser');
var session         = require('express-session');
var mongo           = require('mongodb');
var mongoose        = require('mongoose');
var request = require('request');
var time = require('time');
var now = new time.Date();
now.setTimezone("Asia/Calcutta");
const mongojs = require('mongojs')
const Chapi = require('whatsapp-chapi');
const rechargefn = require("./recharge.js") 

// ======================Mysql DataBase ========================
var con             = require('./database/db');
var User = require('./models/user');
// =========================================================
users = [];
connections = [];
var username;



app.use(express.static(path.join(__dirname, 'public')));
app.use( bodyParser.json() );
app.use(bodyParser.urlencoded({ extended: true}));
app.use(session({secret: 'tom-riddle'}));
//Set up default mongoose connection
app.set('views', path.join(__dirname, 'public'));
app.set('view engine', 'ejs');

app.get('/' , function (req , res) {
    authenticate(req , res);
});

app.get('/chat_start' , function (req , res) {

    authenticate(req , res);
    

});
app.post('/sucess' , function (req , res) {
    const db = mongojs('whatsapp', ['hackers'])
    var personInfo = req.body;
    var myquerywh = {"wa_id": personInfo['wa_id']};
    if(personInfo['message']==1){
     sendmessage(personInfo['wa_id'],'Your '+personInfo['opr']+' postpaid mobile bill worth rs. '+personInfo['amount']+' is successfully paid. We can automate your bill payment process &s ave you from remembering your payment date every month. Just sign up for our 100% auto debit mandate process & we will take care of your bill payment hassles in future. Would you like to go ahead with the Autodebit process now? \n 1️⃣  yes \n 2️⃣  No');
     res.send("ok");

     
        var newvalueswh = { $set: {"trans": 1} };
        
    }else{
        var newvalueswh = { $set: {"usblock": 7} };

        var myquerywh1 = {"wa_id": personInfo['wa_id']};
        var newvalueswh1 = { $set: {"usblock":7} };
        queryUPdateProcess(myquerywh1,newvalueswh1);

        sendmessage(personInfo['wa_id'],'Your '+personInfo['opr']+' postpaid mobile bill worth rs. '+personInfo['amount']+' is Failed ');  
    }
    queryUPdateProcessMobile(myquerywh,newvalueswh);


    });
app.get('/oprdata' , function (req , res) {

    postdata = JSON.stringify({"ServiceTypeID":2});
     var options = {
        'method': 'POST',
        'url': 'http://205.147.103.18:8080/hundi/rest/stateless/getOperatorForWhatsup',
        'headers': {
        'authkey': 'G4s4cCMx2aM7lky1',
        'Content-Type': 'application/json',
        'versioncode': '10',
        'Cookie': 'JSESSIONID=BDA017557EDE1B7759B69B0BF8DD410B'
        },
        body: JSON.stringify({"volley":postdata})

        };
    request(options, function (error, response) {
         if (error) throw new Error(error);
        console.log(response);
        var responsedatwers =  response.body;
            var responsedatwer = JSON.parse(responsedatwers);
            console.log(responsedatwer);

         });
    res.send("tygai");
    });
app.post('/weblive' , function (req , res) {
    const db = mongojs('whatsapp', ['hackers'])
    var personInfo = req.body;
        var userAlty =0;
    if(personInfo['contacts']){
        profile = personInfo['contacts'][0]['profile']
        messages = personInfo['messages'][0]
        personInfojson = {"name":profile['name'],"wa_id":personInfo['contacts'][0]['wa_id'],"from":messages['from'],"text":messages['text'],"text":messages['text']['body'],"timestamp":messages['timestamp'],"type":messages['type'],"recive":'recive'}
    console.log(personInfojson);
    db.hackers.insert(personInfojson);
 

    db.collection("wh_users").findOne({"wa_id":personInfo['contacts'][0]['wa_id']}, function(err, result) {
    if (err) throw err;
    var ts = Math.floor(new Date().getTime() / 1000);
    if(result){
        if(result['user_bot']){
     userAlty = result['user_bot'];
}

        var myquery = {"wa_id": personInfo['contacts'][0]['wa_id'] };
        var newvalues = { $set: {"usertimestmp": ts} };
        db.collection("wh_users").updateOne(myquery, newvalues, function(err, res) {
        if (err) throw err;
        console.log("1 5555555555document updated");
        db.close();
        });


    }else{
        console.log("App Start");
          personInfoInsert = {"name":profile['name'],"wa_id":personInfo['contacts'][0]['wa_id'],"usertimestmp": ts,'user_bot':0}
                db.wh_users.insert(personInfoInsert);

    }
    console.log("RRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR");
   
      qoertData(personInfo,messages);


     io.sockets.emit('new-message_'+userAlty, {msg: messages['text']['body'] , username : profile['name'],wa_id: personInfo['contacts'][0]['wa_id']});
     io.sockets.emit('initial-single-users_'+userAlty, {wa_id: personInfo['contacts'][0]['wa_id'] , name : profile['name'],msg: messages['text']['body']});
    db.close();
    });
    


 res.sendFile(__dirname + '/public/post.html');
    
}
    

    res.send("kkkkkkkkkkkkkkkkk");

   

    authenticate(req , res);

});
app.get('/login' , function (req , res) {
    var chatresponse = chatUserReuest('https://viralpitch.co/ne.php',{});
         console.log("hhhhhhhhhhhhhhhhhhhhh");
         console.log(chatresponse);
    authenticate(req , res);
});

app.post('/login' , function (req , res) {
    login(req , res);
});

app.post('/userbot' , function (req , res) {
    const db = mongojs('whatsapp', ['sub_users'])
var ts = Math.floor(new Date().getTime() / 1000);

var personInfo = req.body;
var value = parseInt(personInfo.id);
var myquery = {"wa_id": personInfo.userwaid };
var newvalues = { $set: {"usertimestmp_bot": ts,"user_bot": value} };
db.collection("wh_users").updateOne(myquery, newvalues, function(err, res) {
if (err) throw err;
console.log("1 document updated");
db.close();
});



res.send("y");
});
app.get('/logout', function (req, res) {
    delete req.session.user;
    res.redirect('/login');
});

function autoreset(mobileno,name){


var ts = Math.floor(new Date().getTime() / 1000);
     var myquerywh1 = {"wa_id": mobileno,"usblock":0};
        var newvalueswh1 = { $set: {"usblock":7} };
        queryUPdateProcess(myquerywh1,newvalueswh1);
        console.log(newvalueswh1);

        var myquerywh = {"wa_id": mobileno,"usblock":0};
        var newvalueswh = { $set: {"usblock":7} };
        queryUPdateProcessMobile(myquerywh,newvalueswh);

        sendmessage(mobileno,' Dear *'+name+'*, This is your *Main Menu*, What would you like to do today ( *Choose from below* ): \n \n 1️⃣  Delhi Metro Card Recharge  \n 2️⃣  Postpaid Mobile Bill Payment \n 3️⃣  DTH Recharge  \n 4️⃣  Broadband Bill Payment \n 5️⃣  Fast Tag Recharge \n 6️⃣  Insurance Policy Payment \n 7️⃣  Electricity | Gas Bill Payment \n 0️⃣  To reach to the main menu any time during the conversation');          

        personInfoprocess = {"step":'',"wa_id":mobileno,"usertimestmp": ts,'usblock':0}
        queryPaymentInsert(personInfoprocess);
}

function qoertData(personInfo,messages){

    console.log("hhhhhhhhhh99999999999999999999999999999999999999999");
    var ts = Math.floor(new Date().getTime() / 1000);
    const db = mongojs('whatsapp', ['hackers']);


    

    var profile = personInfo['contacts'][0]['profile'];
    var username = profile['name'];
    var mobileno = personInfo['contacts'][0]['wa_id']
    var xm=messages['text']['body'];
    var xmv = parseInt(xm);
    console.log("PPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPP");
    console.log(xm);
    if(xm=='0'){
        console.log("EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPP");
        var myquerywh = {"wa_id": personInfo['contacts'][0]['wa_id'],'usblock':0};
        var newvalueswh = { $set: {"usblock": 7} };
        queryUPdate(myquerywh,newvalueswh);
        queryUPdateProcessMobile(myquerywh,newvalueswh);
        queryUPdateProcessDth(myquerywh,newvalueswh);

        
        updateStepRepet(personInfo['contacts'][0]['wa_id'],db);
    }

    db.collection("wh_users_process").findOne({"wa_id":personInfo['contacts'][0]['wa_id'],'usblock':0}, function(errd, resultout) {

    console.log({"wa_id":personInfo['contacts'][0]['wa_id']});

    
     if(resultout){ 


        sendStep(personInfo,messages);
      
 }else{
     console.log("send Message");
     customMenu(mobileno,username);
       
 

 }
});
}
function customMenu(mobileno,username){
var ts = Math.floor(new Date().getTime() / 1000);
personInfoInsertnt = {"name":'',"wa_id":mobileno,
"usertimestmp": ts,'emailid':'',"autopay":0,"dob":'','pancard':'',"address":'','panverify':'','registration':0,'usblock':0,'stepProcess':0}
 queryInsert('wh_users_process',personInfoInsertnt);
 sendmessage(mobileno,' Dear *'+username+'*, This is your *Main Menu*, What would you like to do today ( *Choose from below* ): \n \n 1️⃣  Delhi Metro Card Recharge  \n 2️⃣  Postpaid Mobile Bill Payment \n 3️⃣  DTH Recharge  \n 4️⃣  Broadband Bill Payment \n 5️⃣  Fast Tag Recharge \n 6️⃣  Insurance Policy Payment \n 7️⃣  Electricity | Gas Bill Payment \n 8️⃣  FAQ \n 0️⃣  To reach to the main menu any time during the conversation');          

}


function sendStep(personInfo,messages){

     console.log("hhhhhhhhhh99999999999999999999999999999999999999999");
    var ts = Math.floor(new Date().getTime() / 1000);
    const db = mongojs('whatsapp', ['hackers']);
    var profile = personInfo['contacts'][0]['profile'];
    var username = profile['name'];
    var mobileno = personInfo['contacts'][0]['wa_id'];
    var xm=messages['text']['body'];
    var xmv = parseInt(xm);

    db.collection("wh_users_step").findOne({"wa_id":personInfo['contacts'][0]['wa_id'],'usblock':0}, function(errd, resultout) {

   
         if(resultout){ 
             if(resultout['step']==2){
                console.log("MMMMMMMMMMMMMMMMMMMMMMMMMMMMsssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss");
                postPaidmobile(personInfo,messages,username);
             }
             if(resultout['step']==3){
                console.log("DTHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH");
                dthRecharge(personInfo,messages,username);
             }
            if(resultout['step']==8){
                console.log("again Question Datatta");
                var xm=messages['text']['body'];
                var xmv = parseInt(xm);
                console.log(resultout['process']);
                console.log({faqtype:resultout['process'],faqkey:xmv});
                if(parseInt(resultout['process'])==888){
                    console.log("WRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR");
                    if(xmv==8){
                       faqData(mobileno,0,0,1,db,8);
                    }else{
                        var wrngmassage="Wrong input"+' \n 0️⃣  for Main Menu \n 8️⃣  for Faq Menu';
                        sendmessage(mobileno,wrngmassage);
                    }

                }else{

                db.collection("sub_faqs").findOne({faqtype:resultout['process'],faqkey:xm}, function(err, result) {
                    console.log(result);
                if(result){
                    faqData(mobileno,0,result['unique_id'],1,db,8);
                }else{
                    faqData(mobileno,0,parseInt(resultout['process']),1,db,8);
                    console.log("NODATAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA");
                }
                });
            }    
            }         
        }else{
            console.log("ddddddddddddddddddddddddddd");
            var xm=messages['text']['body'];
            var xmv = parseInt(xm);
            if(xmv>=0 && xmv<9){
                if(xmv==8){
                   faqData(mobileno,0,0,1,db,8);
                }
                if(xmv==2){
                postPaidmobile(personInfo,messages,username);
                }
                if(xmv==3){
                dthRecharge(personInfo,messages,username);
                }

            }else{
                sendmessage(mobileno,' Dear *'+username+'*, This is your *Main Menu*, What would you like to do today ( *Choose from below* ): \n \n 1️⃣  Delhi Metro Card Recharge  \n 2️⃣  Postpaid Mobile Bill Payment \n 3️⃣  DTH Recharge  \n 4️⃣  Broadband Bill Payment \n 5️⃣  Fast Tag Recharge \n 6️⃣  Insurance Policy Payment \n 7️⃣  Electricity | Gas Bill Payment \n 8️⃣  FAQ \n 9️⃣  To reach to the main menu any time during the conversation');          
            }

        }


   });


      console.log("ssssssssssssssssssssssssskkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk");
        
}
function RechargeData(mobileno,mesaage,messagestep,messagein,db,stepvalue){
    sendmessage(mobileno,"\n 1️⃣  for whatsno or Enter Mobile No.");
    }
function messageKeyAssign(valua){
    var dfltval = valua+'️⃣';
    if(valua==10){
        console.log("8)))))))))))))))))))))))))))))))))");
         dfltval = '1️⃣0️⃣ ';
    }else if(valua==11){
        //console.log("8)))))))))))))))))))))))))))))))))");
         dfltval = '1️⃣1️⃣ ';
    }else if(valua==12){
        //console.log("8)))))))))))))))))))))))))))))))))");
         dfltval = '1️⃣2️⃣ ';
    }else if(valua==13){
        //console.log("8)))))))))))))))))))))))))))))))))");
         dfltval = '1️⃣3️⃣ ';
    }else if(valua==14){
        //console.log("8)))))))))))))))))))))))))))))))))");
         dfltval = '1️⃣4️⃣ ';
    }
    else if(valua==15){
        //console.log("8)))))))))))))))))))))))))))))))))");
         dfltval = '1️⃣5️⃣ ';
    } 
    else if(valua==16){
        //console.log("8)))))))))))))))))))))))))))))))))");
         dfltval = '1️⃣6️⃣ ';
    } 
    else if(valua==17){
        //console.log("8)))))))))))))))))))))))))))))))))");
         dfltval = '1️⃣7️⃣ ';
    } 
    else if(valua==18){
        //console.log("8)))))))))))))))))))))))))))))))))");
         dfltval = '1️⃣8️⃣ ';
    } 
    else if(valua==19){
        //console.log("8)))))))))))))))))))))))))))))))))");
         dfltval = '1️⃣9️⃣ ';
    } 
    else if(valua==20){
        //console.log("8)))))))))))))))))))))))))))))))))");
         dfltval = '2️⃣0️⃣ ';
    }  
    return dfltval;
}

function faqData(mobileno,mesaage,messagestep,messagein,db,stepvalue){
var ts = Math.floor(new Date().getTime() / 1000);
  if(messagein==1){
    console.log("INTTTTTTTTTTTTTTTTTTTTTTTT");

    db.collection("sub_faqs").find({faqtype:messagestep}, function(err, result) {
        if (err) throw err;
           
            var meaasgeout = '';
            var okmassage = ''
            for (var i = 0; i < result.length; i++) {
                if(result[i]['faqkey']=='ok'){
                    okmassage=result[i]['faqvalue']+' \n 0️⃣  for Main Menu \n 8️⃣  for Faq Menu';
                }
                meaasgeout = meaasgeout+' \n'+ messageKeyAssign(result[i]['faqkey'])+result[i]['faqvalue'];
            }
            var processmsg = messagestep;
            if(okmassage!=''){
                meaasgeout=okmassage;
                processmsg = 888;
            }
           console.log(meaasgeout)
            sendmessage(mobileno,meaasgeout); 
            var myquerywh = {"name":'',"wa_id":mobileno,"usertimestmp": ts,'step':stepvalue,'usblock':0,'process':processmsg};
            queryStepRepet(myquerywh,mobileno,db);
            
        });

  }else{

  }


}

function queryStepRepet(value,mobileno,db){
      
    updateStepRepet(mobileno,db);
    db.wh_users_step.insert(value);

}
function updateStepRepet(mobileno,db){
    var myquerywh = {"wa_id": mobileno,'usblock':0};
    var newvalueswh = { $set: {"usblock": 1} };
     db.collection("wh_users_step").update(myquerywh, newvalueswh, function(err, res) {
        if (err) throw err;
        console.log("1 eeeeeedocument updated");
        db.close();
        });

}



function queryUPdate(myquery,newvalues){
    console.log("777777777777777777777777777777777777777");

    const db = mongojs('whatsapp', ['hackers'])
     db.collection("wh_users_process").updateOne(myquery, newvalues, function(err, res) {
        if (err) throw err;
        console.log("1 eeeeeedocument updated");
        db.close();
        });

}

function paymentProcess(personInfo,messages,userName){
    const db = mongojs('whatsapp', ['hackers'])
    var ts = Math.floor(new Date().getTime() / 1000);
    db.collection("wh_payment_process").findOne({"wa_id":personInfo['contacts'][0]['wa_id'],'usblock':0}, function(errd, resultout) {

    console.log({"wa_id":personInfo['contacts'][0]['wa_id']});

    
     if(resultout){
       if(resultout['step']==''){

        var myquerywh = {"wa_id": personInfo['contacts'][0]['wa_id'],'usblock':0};
        var newvalueswh = { $set: {"step": parseInt(messages['text']['body'])} };
        queryUPdateProcess(myquerywh,newvalueswh);

        dataPayment(parseInt(messages['text']['body']),personInfo,messages,userName);

       }else{
       console.log("################################################");      
         dataPayment(parseInt(resultout['step']),personInfo,messages,userName);
     }
         

     }else{

        personInfoprocess = {"step":'',"wa_id":personInfo['contacts'][0]['wa_id'],"usertimestmp": ts,'usblock':0}
        queryPaymentInsert(personInfoprocess);
        sendmessage(personInfo['contacts'][0]['wa_id'],' Dear '+userName+', This is your Main Menu, What would you like to do today: -1 Delhi Metro Card Recharge | New Card Issuance 2 - Postpaid Mobile Bill Payment 3 -DTH Recharge | Cable TV Payment 4 -Broadband Bill | Landline Bill Payment 5 -Fast Tag Recharge 6 -Insurance Policy Payment 7 -Electricity | Gas | Water Bill Payment 8 -Press 9 to reach to the main menu, Press 8 to return to the last step');
                

     }
 });

}
function queryUPdateProcess(myquery,newvalues){
    console.log("777777777777777777777777777777777777777");

    const db = mongojs('whatsapp', ['hackers'])
     db.collection("wh_payment_process").updateOne(myquery, newvalues, function(err, res) {
        if (err) throw err;
        console.log("1 eeeeeedocument updated");
        db.close();
        });

}

function queryUPdateProcessDth(myquery,newvalues){
    console.log("777777777777777777777777777777777777777");

    const db = mongojs('whatsapp', ['hackers'])
     db.collection("wh_payment_process_dth").updateOne(myquery, newvalues, function(err, res) {
        if (err) throw err;
        console.log("1 eeeeeedocument updated");
        db.close();
        });

}

function queryUPdateProcessMobile(myquery,newvalues){
    console.log("777777777777777777777777777777777777777");

    const db = mongojs('whatsapp', ['hackers'])
     db.collection("wh_payment_process_mobile").updateOne(myquery, newvalues, function(err, res) {
        if (err) throw err;
        console.log("1 eeeeeedocument updated");
        db.close();
        });

}

function jsonResponse(postdata){
        var options = {
            'method': 'POST',
            'url': 'http://205.147.103.18:8080/hundi/rest/stateless/getOperatorByMobileNumber',
            'headers': {
            'authkey': 'G4s4cCMx2aM7lky1',
            'Content-Type': 'application/json',
            'versioncode': '10',
            'Cookie': 'JSESSIONID=BDA017557EDE1B7759B69B0BF8DD410B'
            },
            body: JSON.stringify({"volley":postdata})

            };
            return options;
}
function opreatorselect(mobileno,db){
      db.collection("wh_mobile_operator").find(function(errd, resultout) {

    var meaasgeout = '';
    var okmassage = ''
    for (var i = 0; i < resultout.length; i++) {
        console.log(resultout[i]);
        var msgs = parseInt(i)+1;       
        meaasgeout = meaasgeout+' \n'+ messageKeyAssign(resultout[i]['Operatorkey'])+resultout[i]['OperatorName'];
        console.log(meaasgeout);
    }
 sendmessage(mobileno,'Please select your operator \n '+meaasgeout);
});
}
function circleselect(mobileno,db){
      db.collection("wh_mobile_circle").find(function(errd, resultout) {

    var meaasgeout = '';
    var okmassage = ''
    for (var i = 0; i < resultout.length; i++) {
        console.log(resultout[i]);
        var msgs = parseInt(i)+1;       
        meaasgeout = meaasgeout+' \n'+ messageKeyAssign(resultout[i]['sndky'])+resultout[i]['RegionName'];
        console.log(meaasgeout);
    }
 sendmessage(mobileno,'Please select your Region Name \n '+meaasgeout);
});
}


function mobileNumbercheck(mobilenosend,personInfo,db){

    
     postdata = JSON.stringify({"mobileNumber":mobilenosend,"eventIs":false});
        var options = jsonResponse(postdata);
        request(options, function (error, response) {
            if (error) throw new Error(error);
            var responsedatwers =  response.body;
            var responsedatwer = JSON.parse(responsedatwers);
            console.log(responsedatwer);
            if(responsedatwer['statusCode']=='400'){
                opreatorselect(personInfo['contacts'][0]['wa_id'],db);
                
           
           
            var newvalueswh = { $set: {"mobileno": personInfo['contacts'][0]['wa_id']} };
          
            }else if(responsedatwer['statusCode']=='200') {
                var repnsedat =JSON.parse(responsedatwer['response']);

                var newvalueswh = { $set: {"mobileno": personInfo['contacts'][0]['wa_id'],"opttemp": repnsedat['operateName'],"circletemp": repnsedat['operatorCircle']} };

                var optname = repnsedat['operateName'];
                var optclcl = repnsedat['operatorCircle'];
              sendmessage(personInfo['contacts'][0]['wa_id'],'Dear User  your operator '+optname+' and circle '+optclcl+'  \n Y️⃣  Yes \n N️⃣  No \n ');
            }
             var myquerywh = {"wa_id": personInfo['contacts'][0]['wa_id'],'usblock':0};
               queryUPdateProcessMobile(myquerywh,newvalueswh);
    });

}
function postPaidmobile(personInfo,messages,userName){
    console.log("00000000000000000000000000099999999999999999");
    const db = mongojs('whatsapp', ['hackers'])
    var ts = Math.floor(new Date().getTime() / 1000);
    db.collection("wh_payment_process_mobile").findOne({"wa_id":personInfo['contacts'][0]['wa_id'],'usblock':0}, function(errd, resultout) {

    console.log({"wa_id":personInfo['contacts'][0]['wa_id']});
    if(resultout){
        console.log("000000000000000000000000000888888888888");
        console.log(resultout);
        if(resultout['mobiletemp']==''){
            var mobiledata = personInfo['contacts'][0]['wa_id'];
            if(messages['text']['body']=='Y'){
            var mobilenosend = mobiledata.substr(2);
              mobileNumbercheck(mobilenosend,personInfo,db);
              var newvalueswh = { $set: {"mobileno": mobilenosend,"mobiletemp":'Y'} };
              
            }else{
             var newvalueswh = { $set: {"mobiletemp":'Y'} };
              sendmessage(personInfo['contacts'][0]['wa_id'],'Please enter 10 digit mobile number');
            }
        var myquerywh = {"wa_id": mobiledata,'usblock':0};
        queryUPdateProcessMobile(myquerywh,newvalueswh);
        }
        else if(resultout['mobileno']==''){
            var phoneno = /^\d{10}$/;
            if(messages['text']['body'].match(phoneno)){
            //var jkk = rechargefn.opratorResponseCheck(messages['text']['body']);
            //console.log(jkk);
            mobileNumbercheck(messages['text']['body'],personInfo,db);
    
      }else{
        sendmessage(personInfo['contacts'][0]['wa_id'],'Please enter valid 10 digit mobile number');

      }

        }
        else if(resultout['operator']==''){
            if(messages['text']['body']=='Y'){
            var oprtter = resultout['opttemp'];
            var oprtcircle = resultout['circletemp'];
            var myquerywh = {"wa_id": personInfo['contacts'][0]['wa_id'],'usblock':0};
            var newvalueswh = { $set: {"operator": oprtter,"region":oprtcircle} };
            queryUPdateProcessMobile(myquerywh,newvalueswh);
            sendmessage(personInfo['contacts'][0]['wa_id'],'Please enter your payment amount in INR');
            }else if(messages['text']['body']=='N'){
            opreatorselect(personInfo['contacts'][0]['wa_id'],db);
            }else{
            var messgint = parseInt( messages['text']['body']);
            if(messgint>=1 && messgint<=11){
            circleselect(personInfo['contacts'][0]['wa_id'],db);
            //sendmessage(personInfo['contacts'][0]['wa_id'],'Please enter your payment amount in INR');
            var myquerywh = {"wa_id": personInfo['contacts'][0]['wa_id'],'usblock':0};
            var newvalueswh = { $set: {"operator": messages['text']['body']} };
            queryUPdateProcessMobile(myquerywh,newvalueswh);
            }else{
            opreatorselect(personInfo['contacts'][0]['wa_id'],db);
            }
    }

        }
        else if(resultout['region']==''){

            var messgint = parseInt( messages['text']['body']);
            if(messgint>=1 && messgint<=23){
            sendmessage(personInfo['contacts'][0]['wa_id'],'Please enter your payment amount in INR');

            var myquerywh = {"wa_id": personInfo['contacts'][0]['wa_id'],'usblock':0};
            var newvalueswh = { $set: {"region": messages['text']['body']} };
            queryUPdateProcessMobile(myquerywh,newvalueswh);
            }else{
            circleselect(personInfo['contacts'][0]['wa_id'],db);
            }

        }
        else if(resultout['amount']==''){
            if(/^-?\d+$/.test(messages['text']['body'])){
            sendmessage(personInfo['contacts'][0]['wa_id'],'https://viralpitch.co/ne.php');

        var myquerywh = {"wa_id": personInfo['contacts'][0]['wa_id'],'usblock':0};
        var newvalueswh = { $set: {"amount": messages['text']['body']} };
        queryUPdateProcessMobile(myquerywh,newvalueswh);
    }else{
        sendmessage(personInfo['contacts'][0]['wa_id'],'Please enter valid payment amount in INR');

    }

        }
    }else{
        console.log("000000000000000000000000000*******************************************************************************************");

        personInfoprocessall = {"wa_id":personInfo['contacts'][0]['wa_id'],'usblock':0,"mobiletemp":'',"mobileno":'','region':'',"operator":'',"usertimestmp": ts,'amount':0,'trans':0,'autopay':0,'autolink':0,'autoprocess':0,'autolink':0}
        //console.log(personInfoprocess);
        queryPaymentmobileInsert(personInfoprocessall);
        sendmessage(personInfo['contacts'][0]['wa_id'],'Autope: Do you want to recharge for '+personInfo['contacts'][0]['wa_id']+'  \n Y️⃣  Yes \n N️⃣  No \n ');

        var myquerywh = {"name":'',"wa_id":personInfo['contacts'][0]['wa_id'],"usertimestmp": ts,'step':2,'usblock':0,'process':0};
         queryStepRepet(myquerywh,personInfo['contacts'][0]['wa_id'],db);

    }

});



}


function dthRecharge(personInfo,messages,userName){
    console.log("00000000000000000000000000099999999999999999");
    const db = mongojs('whatsapp', ['hackers'])
    var ts = Math.floor(new Date().getTime() / 1000);
    db.collection("wh_payment_process_dth").findOne({"wa_id":personInfo['contacts'][0]['wa_id'],'usblock':0}, function(errd, resultout) {

    console.log({"wa_id":personInfo['contacts'][0]['wa_id']});
    if(resultout){
        console.log("000000000000000000000000000888888888888");
        if(resultout['mobileno']==''){
            var phoneno = /^[0-9]+$/;
            if(messages['text']['body'].match(phoneno)){
            sendmessage(personInfo['contacts'][0]['wa_id'],'Please select your operator \n 1️⃣  Airtel \n 2️⃣  Tata Sky \n 3️⃣  Dish Tv \n 4️⃣  Videcon D2h \n 5️⃣  Jio Dth');

        var myquerywh = {"wa_id": personInfo['contacts'][0]['wa_id'],'usblock':0};
        var newvalueswh = { $set: {"mobileno": messages['text']['body']} };
        queryUPdateProcessDth(myquerywh,newvalueswh);
      }else{
        sendmessage(personInfo['contacts'][0]['wa_id'],'Please enter valid DTH number');

      }

        }
        else if(resultout['operator']==''){
            var messgint = parseInt( messages['text']['body']);
        if(messgint==1 || messgint==2 || messgint==3 || messgint==4){
        sendmessage(personInfo['contacts'][0]['wa_id'],'Please enter your payment amount in INR');

        var myquerywh = {"wa_id": personInfo['contacts'][0]['wa_id'],'usblock':0};
        var newvalueswh = { $set: {"operator": messages['text']['body']} };
        queryUPdateProcessDth(myquerywh,newvalueswh);
        }else{
            sendmessage(personInfo['contacts'][0]['wa_id'],'Please select your operator \n 1️⃣  Airtel \n 2️⃣  Tata Sky \n 3️⃣  Dish Tv \n 4️⃣  Videcon D2h \n 5️⃣  Jio Dth');
        }

        }
        else if(resultout['amount']==''){
            if(/^-?\d+$/.test(messages['text']['body'])){
            sendmessage(personInfo['contacts'][0]['wa_id'],'https://viralpitch.co/ne.php');

        var myquerywh = {"wa_id": personInfo['contacts'][0]['wa_id'],'usblock':0};
        var newvalueswh = { $set: {"amount": messages['text']['body']} };
        queryUPdateProcessDth(myquerywh,newvalueswh);
    }else{
        sendmessage(personInfo['contacts'][0]['wa_id'],'Please enter valid payment amount in INR');

    }

        }
    }else{
        console.log("000000000000000000000000000*******************************************************************************************");

        personInfoprocessall = {"wa_id":personInfo['contacts'][0]['wa_id'],'usblock':0,"mobileno":'',"operator":'',"usertimestmp": ts,'amount':0,'trans':0,'autopay':0,'autolink':0,'autoprocess':0,'autolink':0}
        //console.log(personInfoprocess);
        queryPaymentDthInsert(personInfoprocessall);
        sendmessage(personInfo['contacts'][0]['wa_id'],'Please enter your DTH number');

        var myquerywh = {"name":'',"wa_id":personInfo['contacts'][0]['wa_id'],"usertimestmp": ts,'step':3,'usblock':0,'process':0};
         queryStepRepet(myquerywh,personInfo['contacts'][0]['wa_id'],db);

    }

});



}


function dataPayment(outstep,personInfo,messages,userName){


 if(outstep==1){
        metroCard();

     }else if(outstep==2){
        postPaidmobile(personInfo,messages,userName);
        
     }else if(outstep==3){
         dthRecharge();
        
     }else if(outstep==4){
        broadBand();
        
     }else if(outstep==5){
        tagRecharge();
        
     }
     else if(outstep==6){
        insurancePolicy();
        
     }
     else if(outstep==7){
        electricityBill();
        
     }else{
        autoreset(personInfo['contacts'][0]['wa_id'],userName);

     }

}

function chatUserReuest(url,postdata){
var responsedat = 1;
var options = {
            'method': 'POST',
            'url': url,
            'headers': {
            'Content-Type': 'application/json',
            },
            body: JSON.stringify(postdata)

            };
            request(options, function (error, response) {
            if (error) throw new Error(error);
            
            var responsedat =  response.body;
            console.log(responsedat);
            return response.body;
            });

            console.log(responsedat);


}
function queryPaymentDthInsert(value){
    console.log(value);

   
    const db = mongojs('whatsapp', ['hackers'])
    db.wh_payment_process_dth.insert(value);

}

function queryPaymentmobileInsert(value){
    console.log(value);

   
    const db = mongojs('whatsapp', ['hackers'])
    db.wh_payment_process_mobile.insert(value);

}


function queryPaymentInsert(value){

   
    const db = mongojs('whatsapp', ['hackers'])
    db.wh_payment_process.insert(value);

}

function queryInsert(tbalenam,value){
    console.log("777777777777777777777777777777777777777");

    const db = mongojs('whatsapp', ['hackers'])
    db.wh_users_process.insert(value);

}
function sendmessage(mno,message){


    var options = {
            'method': 'POST',
            'url': 'https://whatsapp-api-269.clare.ai/v1/messages',
            'headers': {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer eyJhbGciOiAiSFMyNTYiLCAidHlwIjogIkpXVCJ9.eyJ1c2VyIjoiYWRtaW4iLCJpYXQiOjE2MDMxMDEzNzMsImV4cCI6MTYwMzcwNjE3Mywid2E6cmFuZCI6LTM0MDE3MDY2MTUxOTI0NDk1NTB9.k_4CUTonmcdEC5KAf0sHrmj6cII8IHYC7H6OOHKEdW0',
            'Cookie': '__cfduid=d4b43d600172977e77e8ebe0426da19731598596425'
            },
            body: JSON.stringify({"to":mno,"type":"text","recipient_type":"individual","text":{"body":message}})

            };
            request(options, function (error, response) {
            if (error) throw new Error(error);
             
            const db = mongojs('whatsapp', ['hackers'])

            
            var ts = Math.floor(new Date().getTime() / 1000);
            personInfojson = {"name":'admin',"wa_id":mno,"from":mno,"text":message,"timestamp":ts,"type":'text',"recive":'send'}
            console.log(personInfojson);
            db.hackers.insert(personInfojson);

            });

}

function chat_start(chatfinduser) {
// ===================================Sockets starts  =========================
    io.sockets.on('connection', function (socket) {
        connections.push(socket);
        //console.log("Connected:  %s Socket running", connections.length);
// ====================Disconnect==========================================
        socket.on('disconnect', function (data) {
            connections.splice(connections.indexOf(data), 1);
            //console.log('Disconnected : %s sockets running', connections.length);
        });
        
// ==================initilize data and show================================
        socket.on('initial-messages_'+chatfinduser, function (data) {
            const db = mongojs('whatsapp', ['hackers'])
            console.log(data);
             if(data=='initial'){
            var dffd = '';
            var jsonMessagesall = '';
            db.collection("wh_users").findOne({user_bot:{"$gt":0}},function(err, resultuser) {
            if (err) throw err;
            
            

            db.collection("hackers").find({wa_id:resultuser.wa_id}, function(err, result) {

            if (err) throw err;
                var jsonMessages = JSON.stringify(result);
                 
                io.sockets.emit('initial-message_'+chatfinduser, {msg: jsonMessages,"wa_id":resultuser.wa_id,"wname":resultuser.name});
            });
            
            
             });

            

        }else{
            console.log(data);



            db.collection("hackers").find({wa_id:data}, function(err, result) {

            if (err) throw err;
                var jsonMessages = JSON.stringify(result);
                 
                io.sockets.emit('initial-message_'+chatfinduser, {msg: jsonMessages,"wa_id":data,"wname":'k'});
            });
        }
        



          
             
        });

        socket.on('initial-single-users_'+chatfinduser, function (data) {
        
        io.sockets.emit('initial-single-users_'+chatfinduser, data);
            
        });

        socket.on('initial-users_'+chatfinduser, function (data) {
           

            const db = mongojs('whatsapp', ['hackers'])

             db.collection("wh_users").find({"user_bot":chatfinduser}, function(err, result) {
            if (err) throw err;
                var jsonMessages = JSON.stringify(result);
            ;
                io.sockets.emit('initial-user_'+chatfinduser, {msg: jsonMessages});
            });
        });
        socket.on('username_'+chatfinduser, function (data) {
            socket.emit('username_'+chatfinduser, {username: username});
            //io.sockets.emit('username', {username: username});
        });

//   ============== Send and Save Messages=====================================
        socket.on('send-message_'+chatfinduser, function (data, user,userSend) {
            console.log(data);

            
            
            var options = {
            'method': 'POST',
            'url': 'https://whatsapp-api-269.clare.ai/v1/messages',
            'headers': {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer eyJhbGciOiAiSFMyNTYiLCAidHlwIjogIkpXVCJ9.eyJ1c2VyIjoiYWRtaW4iLCJpYXQiOjE2MDMxMDEzNzMsImV4cCI6MTYwMzcwNjE3Mywid2E6cmFuZCI6LTM0MDE3MDY2MTUxOTI0NDk1NTB9.k_4CUTonmcdEC5KAf0sHrmj6cII8IHYC7H6OOHKEdW0',
            'Cookie': '__cfduid=d4b43d600172977e77e8ebe0426da19731598596425'
            },
            body: JSON.stringify({"to":userSend,"type":"text","recipient_type":"individual","text":{"body":data}})

            };
            request(options, function (error, response) {
            if (error) throw new Error(error);
            console.log(response.body);
            });
            console.log({"to":"917982959619","type":"text","recipient_type":"individual","text":{"body":data}})

            const db = mongojs('whatsapp', ['hackers'])
            
            var ts = Math.floor(new Date().getTime() / 1000);
            personInfojson = {"name":'admin',"wa_id":userSend,"from":userSend,"text":data,"timestamp":ts,"type":'text',"recive":'send'}
            console.log(personInfojson);
            db.hackers.insert(personInfojson);




            
            io.sockets.emit('new-message_'+chatfinduser, {msg: data ,"wa_id":userSend, username : user,'recive':'send'});
        })
    })
}





function login(req,res){
    var post = req.body;
     username  = post.user;
    var password = post.password;
    //console.log(username);
 


    const db = mongojs('whatsapp', ['hackers'])

    db.collection("sub_users").find({"username":username,"status":1}, function(err, result) {
    if (err) throw err;
                 
        if (result) {
            var jsonString = JSON.stringify(result);
            var jsonData = JSON.parse(jsonString);
            if(jsonData[0].password === password) {
                console.log(jsonString);
                req.session.user = post.user;
                req.session.user_bot = jsonData[0].unique_id;
                username = post.user;
                chat_start(jsonData[0].unique_id);
                res.redirect("/chat_start");
            }else  {
                //console.log("user not Identified");
                res.redirect("/login");
            }
        } else {
            res.redirect("/login");
        }
    });
}

function checkuser() {
    if (!req.session.user) {
        return 0;
    }
    else {
        //console.log(req.session.user);
        return req.session.user;
    }
}

function authenticate(req,res){
    //console.log("authenticate called");
    if (!req.session.user) {
        res.sendFile(__dirname + '/public/login.html');
    }
    else {
        //console.log(req.session.user);
        username = req.session.user;
        res.render('chat.ejs',{'data':username,'usersession':req.session.user_bot});
        //res.sendFile(__dirname + '/public/chat.html',{'username':"sssssssssss"});
    }
}
server.listen(4000, function(){
    console.log('listening on *:4000');
});



