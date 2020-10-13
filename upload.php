<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999" xml:lang="en" lang="en">
<head>
    <meta charset ="utf-8"/>
    <meta name="description" content="AWS photo uploader"/>
    <meta name="author" content="Mishal Ismeth"/>
    <!-- viewport is used to allow the browser to accomadate varying screen sizes-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Photo Album </title>
    <link href="styles/styles.css" rel="stylesheet"/>
  </head>
  <header class="w3-container w3-teal">
      <h1>Photo Uploader</h1>
      <h3>Student ID: 101655100</h3>
      <h3>Student Name: Ahamed Mishal Mohammed Ismeth</h3>
  </header>  
    <body>
      <section>
        <form class="w3-container" id="uploadForm" method="post" action="processUpload.php" enctype="multipart/form-data">
          <p>
            <label for="photoTitle">Photo title: </label>
            <input type="text" name="photoTitle" id="photoTitle" required="required" placeholder="e.g:- Campside"/>
          </p>
          <p>
            <label for="photoDescription">Descripiton : </label>
            <input type="text" name="photoDescription" id="photoDescription" required="required"/>
          </p>
          <p>
            <label for="photoDate">Date : </label>
            <input type="date" name="photoDate" id="photoDate" required="required"/>
          </p>
          <p>
            <label for="photoKeywords">Keywords (separated by a comma eg:keyword1,keyword2;etc): </label>
            <input type="text" name="photoKeyword" id="photoKeywords" required="required"/>
          </p>
          <p>
            <label for="photoFile">Select a photo to upload: </label>
            <input type="file" name="photoFile" id="photoFile" required="required"/>
          </p>
          <input type="submit" value="Upload">
        </form>
      </section>
    </body>
  </html>

