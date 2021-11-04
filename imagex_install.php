<?php 
//#####################################################
// CARLOS SANTOS DE AZEVEDO
// Software: Imagex - Website Image Upload & Managment 
// Website Images Managment without database
// CopyRight: Litos Media - Carlos Santos de Azevedo
// Contact: info@litos.top
// 10-2017
//#####################################################
if($_POST['set_folder'] =="ewef34t235756g23eww"){
function imagex_limpar( $string, $separator = '-' )
{
    $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
    $special_cases = array( '&' => 'and', "'" => '');
    $string = mb_strtolower( trim( $string ), 'UTF-8' );
    $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
    $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
    $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
    $string = preg_replace("/[$separator]+/u", "$separator", $string);
    return $string;
}		
 
//Criar pasta principal standart se o user nao screver um path
if(!$_POST['top_folder_path']){	
$top_folder= 'imagex-uploads';
if (!file_exists($top_folder)) {
    mkdir($top_folder, 0755, true);
}
}
	

/* Set the Path*/	
if($_POST['top_folder_path']){
$user_path= $_POST['top_folder_path'];
	
//Criar pasta principal caso nao exista
$top_folder= basename ($user_path);
if (!file_exists($top_folder)) {
    mkdir($top_folder, 0755, true);
}
 
}else $user_path= 'imagex-uploads';
		
	
chmod("imagex_config.php", 0777);	
$str=file_get_contents('imagex_config.php');
$str=str_replace("##TOP_FOLDER_PATH##", $user_path,$str);
if(strlen(trim($_POST['imagex_pw'])) >1 ){
$str=str_replace("##LOGIN_ACTIVE##", '1',$str);
$str=str_replace("##YOUR_PASSWORD##", md5($_POST['imagex_pw']),$str);} else {$str=str_replace("##LOGIN_ACTIVE##", '0',$str);}
file_put_contents('imagex_config.php', $str);
	
		
//Criar a primeira subpasta	
$first_subfolder= imagex_limpar($_POST['first_folder']); 
$first_subfolder_path= $_SERVER['DOCUMENT_ROOT']."/".$user_path.'/'.$first_subfolder.'/';
if (!file_exists($first_subfolder_path)) {
    mkdir($first_subfolder_path, 0755, true);
}
		 	
	
chmod("imagex_install.php", 0777); 
unlink('imagex_install.php');	
chmod("imagex_config.php", 0755);
	
$msg= '<div class="text-center alert alert-success" role="alert">ImageX was sucessfully installed!</div>';	
echo '<script> setTimeout(function () { window.location.href = "imagex.php"; }, 1000);</script>';	
}
 
 
/********************************* 
Check Server Requirements
*********************************/	
 /*Check that PHP version is at leat 7.0*/
if (defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION >= 5) 
{
  $phpversion= '5';
} 
else 
{
  $msg=  '<div class="text-center alert alert-danger" role="alert">Please install or activate PHP5.3 or newer on your server before the installation.</div>';
}
 
/*Check EXEC*/
if (extension_loaded('gd') && function_exists('gd_info')) {
	$GD_function='ok';}
else $msg= '<div class="text-center alert alert-danger" role="alert">You need the GD Library Function enabled on your server.</div>';
 
/********************************* 
END Check Server Requirements
*********************************/	
 


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content=" ">
    <meta name="keywords" content="">

    <title>Imagex Install</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link href="assets/css/core.css" rel="stylesheet">

    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Raleway:100,300,400,500,600,800%7COpen+Sans:300,400,500,600,700,800%7CMontserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

</head>

<!-- Main container -->

<body>
    <div class="container-fluid" style="background: #ECEFF5; padding-top: 50px; padding-bottom: 200px;">

        <div class="col-sm-12">
            <div class="box" style="max-width: 400px; margin: auto">
                <div class="box-header" id="box-header" style="margin-bottom: 0px;">
                    <h6 class="box-title"> <img src="assets/img/Logo-pequeno.png"></h6>

                    <div class="container"> <small><?php echo _('Installation Wizard');?></small></div>
                </div>

                <div class="box-body">
                    <?php echo $msg;?>
                        <?php if($phpversion !='5'  || $GD_function !='ok'){die;}?>
                            <br>
                            <form action="imagex_install.php" method="post">

                                <div class="form-group">

									
									
									 

                                    <h6 class="box-title "><strong>Password protected?</strong><br> <small>(If you set a password, the simple login system will be activated.)</small></h6>
                                    <input autocomplete="off" class="form-control input-lg" type="password" name="imagex_pw" placeholder="Your Password" value="<?php echo $_POST['imagex_pw'];?>">
									 
 
 
									<div class="clearfix bottom30"></div>
									
									
									
									
                                    <h6 class="box-title"><strong>Top Folder path</strong> 

<small style="font-size: 10px;">Please type the path where the top folder of all images should be placed. <strong>For ex. gallery/my-images</strong>.
If the last folder of the path do not exists, this will be created automatically. Leave blank to use the default folder from Imagex on the root of the website.
</small></h6>

                                    <div style="font-size: 18px">
                                        www.yoursite.tld/
                                        <input style="width:170px;font-size: 15px; border:0px; border-bottom:1px solid #EC6767; " placeholder=" ex. gallery/my-images" type="text" name="top_folder_path" value="<?php echo $_POST['top_folder_path'];?>"> /
                                    </div>

                                    <div class="clearfix"></div>

                                    <h6 class="box-title top30"><strong>Your first Image Folder</strong><br> <small>(Please choose a name for your first Folder where the uploads should be saved.)</small></h6>
                                    <input autocomplete="off" class="form-control input-lg" type="text" name="first_folder" placeholder="Ex.holidays-fotos" value="<?php echo $_POST['first_folder'];?>">
                                </div>
                                <br>

                                <input type="hidden" value="ewef34t235756g23eww" name="set_folder">

                                <button class="btn btn-primary  btn-block" type="submit" style="background:#41C1E5 ">
                                    <?php echo _('Install now');?>
                                </button>

                            </form>

                            <br>
                </div>

            </div>
        </div>
    </div>

    <!-- END Main container -->

</body>

</html>