<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" id='calculator'>
        
        <label for="">cummulative</label>
        <input type="number" name="cummulative">
        <label for="">new transaction</label>
        <input type="number" name='n_transec'>
        <label for="">tax for</label>
        <select name="tax_for" id="">
            <option value="1">Pribadi with NPWP</option>
            <option value="2">Probadi without NPWP</option> 
            <option value="3">PT / CV PKP</option>
            <option value="4">PT / CV NON PKP</option>
        </select>
    <input type="submit" value="submit" id='submit'>
    </form>
    <div class="show">

    </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('#submit').click(function(e){
            var c=$('input[name="cummulative"]').val();
            var t=$('input[name="n_transec"]').val();
            var type=$('select[name="tax_for"]').val();
            
            e.preventDefault();
            $.ajax({
                url:"http://localhost/viral_pitch_tasks_2/tasks/tax_calculator/ajax.php",
                method:"POST",
                data:{'c':c,'t':t,"type":type},
                success:function(res){
                    res=JSON.parse(res);
                    
                    if(type==1|type==2){
                        content="<p>invoice to group m:"+res['invoice_groupm']+"</p>kol payment:"+res['kol_payment']+"<p>tax:"+res['tax']+"</p><p>dpp:"+res['dpp']+"</p>";
                        $('.show').html('');
                        $('.show').html(content);
                        $('#calculator')[0].reset();
                    }
                    else if(type==4){
                        content="<p>invoice to group m:"+res['invoice_groupm']+"</p><p>kol payment:"+res['kol_payment']+"</p><p>pph23"+res['tax']+"</p>";
                        $('.show').html('');
                        $('.show').html(content);
                        $('#calculator')[0].reset();
                    }else if(type==3){
                        content="<p>invoice to group m:"+res['invoice_groupm']+"</p>kol payment:"+res['kol_payment']+"<p>pph23:"+res['tax']+"</p><p>ppn :"+res['ppn']+"</p>";
                        $('.show').html('');
                        $('.show').html(content);
                        $('#calculator')[0].reset();
                    }
                }
            });
        });
    });
</script>
</html>