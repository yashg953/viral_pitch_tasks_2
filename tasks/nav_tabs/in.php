<?php include_once("header.php"); 

include 'config.php';

if(isset($_POST)&&!empty($_POST)){
    
    echo $name=$_POST['fullname'];
    echo $email=$_POST['email'];
    echo $pass=$_POST['pass'];
    echo $cpass=$_POST['cpass'];
    $sql="select * from tackier_taffic where email='".$email."'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        $msg=1;
    }else{
        if($pass==$cpass){
            $sql="INSERT INTO `tackier_taffic`(`username`, `email`, `password`) VALUES ('".$name."','".$email."','".$pass."')";
            mysqli_query($conn,$sql);
        }else{
            
        }
        
    }
}


        $msg=2;

?>

<main>

<section class="ii op br di pp cr">
<div class="ba sd h w fi tp gr ki dh kp yq">
<div class="g -ud-z-1 ye l i rc dc/3 fg gg hg xl zl am"></div>
<div class="g -ud-z-1 p l rc ec/3">
<img src="./content/shape-dotted-light.svg" alt="Dotted" class="nl">
<img src="./content/shape-dotted-dark.svg" alt="Dotted" class="ub ml">
</div>
<div class="animate_top mj ye uf tl ol pl zh hr dh zq" data-sr-id="1" style="visibility: visible; opacity: 1; transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1); transition: opacity 2.8s cubic-bezier(0.5, 0, 0, 1) 0s, transform 2.8s cubic-bezier(0.5, 0, 0, 1) 0s;">
<h2 class="ej dm ri mr aj _a">Login to Your Account</h2>
    <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo'<p >
Wrong Email-id  or Password.
</p>';

}else{
?>
<form action="" method="POST">
<div class="qb be po so le bp ea _n">
<input type="text" placeholder="Username" class="rc ko/2 cg vl kf nf pl jl gl qm il rm gi"><br>
<input type="Email" placeholder="Email" class="rc ko/2 cg vl kf nf pl jl gl qm il rm gi"><br>
<input type="pass" placeholder="Password" class="rc ko/2 cg vl kf nf pl jl gl qm il rm gi"><br>
<input type="cpass" placeholder="Confirm Password" class="rc ko/2 cg vl kf nf pl jl gl qm il rm gi"><br>
</div>
<div class="qb de ee gn re kq">
<div class="qb de pe in">
<label for="supportCheckbox" class="rd qb yd zd bl">
<div class="h">
<input type="checkbox" id="supportCheckbox" class="c">
<div class="box wa kb qb fc bd ee ge cf hf nf pl">
<span class="lj">
<svg width="11" height="8" viewBox="0 0 11 8" fill="none" class="og">
<path d="M10.0915 0.951972L10.0867 0.946075L10.0813 0.940568C9.90076 0.753564 9.61034 0.753146 9.42927 0.939309L4.16201 6.22962L1.58507 3.63469C1.40401 3.44841 1.11351 3.44879 0.932892 3.63584C0.755703 3.81933 0.755703 4.10875 0.932892 4.29224L0.932878 4.29225L0.934851 4.29424L3.58046 6.95832C3.73676 7.11955 3.94983 7.2 4.1473 7.2C4.36196 7.2 4.55963 7.11773 4.71406 6.9584L10.0468 1.60234C10.2436 1.4199 10.2421 1.1339 10.0915 0.951972ZM4.2327 6.30081L4.2317 6.2998C4.23206 6.30015 4.23237 6.30049 4.23269 6.30082L4.2327 6.30081Z" stroke-width="0.4"></path>
</svg>
</span>
</div>
</div>
Keep me signed in
</label>
<a href="<?php echo $domain_name; ?>/signin#">Forgot Password?</a>
</div>
<button class="sb ee je tf rl xk hk ek bj fj xe xg yg">
Create Account
<svg class="ig" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M10.4767 6.16664L6.00668 1.69664L7.18501 0.518311L13.6667 6.99998L7.18501 13.4816L6.00668 12.3033L10.4767 7.83331H0.333344V6.16664H10.4767Z" fill=""></path>
</svg>
</button>
</div>
<div class="mi lf nf pl ua mh">

</div>
</form>
<?php } ?>
</div>
</div>
</section>

</main>

<?php include_once("footer.php"); ?>