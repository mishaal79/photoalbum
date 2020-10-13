<!-- /* 
* PHP file will process the upload of metadata into DB sent by post request
* Date created: 17/05/2019
*
*
*/ -->
<?php

		// function to clean trailing spaces and html elements from data inputs
		function sanitise_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}

		//function to check whether variable is set from POST request
		function checkVarIsSet($string) {

			if (isset($_POST[$string] ) ){
				
				return $_POST[$string];
			}
			else{
				
				return "undefined";
			}
		}
	?>
<!-- // Process the metadata to be uploaded to the DB -->

<?php

	require_once "settings.php";	// Load MySQL log in credentials
	$conn = new mysqli($host,$user,$pwd,$sql_db);	// Log in and use database
	if ($conn->connect_error) // check is database is available for use
	{ 
		echo "<p>Unable to connect to the database.</p>";
		die("Connection failed: ".$conn->connect_error);
	}
//	 else {

		// Declare all local variables

		$table = "photos";
		$photoTitle = sanitise_input(checkVarIsSet("photoTitle"));
		$photoKeyword = sanitise_input(checkVarIsSet("photoKeyword"));
		$photoDescription = sanitise_input(checkVarIsSet("photoDescription"));   
        $photoDate = sanitise_input(checkVarIsSet("photoDate"));
        $photoFileName = $_FILES["photoFile"]["name"]; // assigns the file name of the uploaded file
        $s3BucketObject = "https://d30pwwmcur3wzs.cloudfront.net/".$photoFileName;
        
/*
* Sanitise all html form inputs and assign to variable
* Perform an SQL Insert query to insert all the metadata into the DB
*
*/
    $query = "insert into $table (title,description,date,keywords,reference) 
              values ('$photoTitle','$photoDescription','$photoDate','$photoKeyword','$s3BucketObject')";
  
    $result = $conn->query($query); // perform an insert query and insert the values from the form into DB

    if($result)
    {
      echo "<p>New record created</p>";
    }
    else
    {
      echo "<p>Error: $query <br> $conn->error</p>";
    }
    //close the db connection
    $conn->close();
?>

<!-- // Process to upload the photo file into temp directory -->

<?php
    $target_dir = "photos/";
    $target_file = $target_dir . basename($photoFileName);
    $uploadOk = 1; // variable to determine whether file uploaded was valid
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    //check if file already exists in target directory
    if (file_exists($target_file))
    {
        echo "<p>temporary file already exists.</p>";
        $uploadOk = 0;
    }
    // Allow certain image file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) 
    {
        echo "<p>Only image files are supported</p>";
        $uploadOk = 0;
    } 
    if($uploadOk != 0)
    {
        if (move_uploaded_file($_FILES["photoFile"]["tmp_name"],$target_file))
        {
            echo "<p>File is valid and successfully uploaded to temporary directory</p>";
        }
        else
        {
            echo "<p>Warning : file upload attack</p>";
        }

    }
    else
    {
        echo "<p>temporary file upload failed due to an error</p>";
        echo "<p>Debugging info</p>";
        print_r($_FILES);
    }
?>
<!-- Upload file to S3 bucket using AWS SDK for php -->

<?php

    require_once "vendor/autoload.php"; //includes the sdk as library into php script

   //modules to use

    use Aws\S3\S3Client; //create a client to use s3
    use Aws\S3\Exception\S3Exception;
    use Aws\S3\ObjectUploader;
    use Aws\S3\MultipartUploader;

    //initialise local variables
    $s3Bucket = "cca-assignment"; //s3 bucket name

    $s3_client = new S3Client(['version'=>'latest','region'=>'ap-southeast-2']); // create new client

    //uploading file to s3 bucket
       if (file_exists($target_file))
    {
        $uploader = new MultipartUploader($s3_client,$target_file,['bucket'=>$s3Bucket,'key'=>$photoFileName]);
        try {
            $result = $uploader->upload();
            if ($result["@metadata"]["statusCode"] == '200') {
            print('<p>File successfully uploaded to S3 ' . $result["ObjectURL"] . '.</p>');
            }
        } catch (S3Exception $e) {
            echo "<p>$e</p>";
        }
    }
    else
    {
        echo "<p>File not found: In photos temporary directory</p>";
    } 

?>