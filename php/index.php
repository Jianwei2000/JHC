<?php
    $db_host = "localhost"; //資料庫位置
    $db_username = "root"; //資料庫帳號
    $db_password=""; //資料庫密碼
    $db_name="board";//資料庫名稱

    $db_link = new mysqli($db_host, $db_username, $db_password, $db_name); //連接資料庫

    //測試是否連接成功
    if($db_link -> connect_error == ""){
        // echo "資料庫連接成功!";
        $db_link->set_charset("utf8"); //使用UTF8解析
    }


    $sendToSqlData = "SELECT * FROM msg ORDER BY id DESC";  //變數儲存送給sql的敘述
    $getData =  $db_link -> query($sendToSqlData);

    /************搜尋功能*************/
    $search = "";
    if(isset($_GET['search'])){
        $search = $db_link->real_escape_string($_GET['search']);
    }

    $sendToSqlData = "SELECT * FROM msg WHERE title LIKE '%$search%' ORDER BY id DESC";  //變數儲存送給sql的敘述
    $getData =  $db_link -> query($sendToSqlData);

    /***************post***************/

    if(isset($_POST["action"])){
        // echo "有接受到POST!";
        $saveSql = "INSERT INTO msg (names,title,good,bad) VALUES (?,?,?,?)";
        $toSave = $db_link -> prepare($saveSql);
        $toSave->bind_param("ssss",
        $_POST["names"],
        $_POST["title"],
        $_POST["good"],
        $_POST["bad"]);

        $toSave->execute();
        $toSave->close();

        // 重定向到 index.php
        header("Location: index.php");

    };
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>求職千里眼</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- 字體 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        .header{
            background-color: #f7847e;
            height: 3.5rem;
            
        }
        .header button{
            color:#FF574D;
        }
        .logo{
            font-family: "Pacifico", cursive;
            font-size: 3rem;
            font-style: normal;
        }
        .bgc{
            background-color: #EEEBF0;
        }
        .search{
            background-color: #FF574D;
            height: 9rem;
          
         
        }
        .search-input{
            border-radius: 50px;
            border:0px;
            
        }
        .input-group-prepend {
            background-color: #fff;
            border-radius:50px;
            position: absolute;
            right: 0;
            top:0;
            height: 100%;
            display: flex;
            align-items: center;
        }
        .input-group-text {
            border-radius: 50%;
            background-color: #FF574D;
            color: white;
            border: none;
        }
        textarea {
            width: 100%;
            height: 150px;
        }
    </style>
</head>
<body class="bgc">
        
    <div class="header">
        <button type="button" class="btn btn-light rounded-pill float-end mt-2 me-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
            留下評論
        </button>
    </div>

    <div class="search">
        <div class="container">
            <div class="d-flex">
                <a href="/php" class="logo fw-bold mt-4 text-decoration-none text-white">JHC</a>
                <div class="input-group m-5 ">
                    <div class="col-md-6 position-relative">
                        <form action="" method="get">
                            <input class="search-input shadow form-control py-3" type="text" name="search" placeholder="搜尋公司" aria-describedby="button-search" value="<?php echo htmlspecialchars($search); ?>">
                            <button class="input-group-prepend border-0 " type="submit">
                                <span class="input-group-text" id="button-search">
                                    <i class="bi bi-search"></i>
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="container">
        
        <?php while($item = $getData -> fetch_assoc()){ ?>
            <div class="msg bg-white my-5 p-3 rounded-3 shadow">
                <div class="border-bottom d-flex justify-content-between">

                    

                    <p class="fs-5"><i class="bi bi-person-circle fs-1 text-muted me-2"></i> <?php echo $item["names"]; ?></p>
    
                    <p class="mt-3"><i class="bi bi-building"></i> <?php echo $item["title"]; ?></p>
                </div>
                <h4 class="fw-bold text-primary mt-3">優點:</h4>
                <p><?php echo $item["good"]; ?></p>
                <h4 class="fw-bold text-danger">缺點:</h4>
                <p><?php echo $item["bad"]; ?></p>
            </div>
        <?php } ?>
    </div>

    <!-- Modal -->
    <form action="" method="post">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">為公司寫下評論</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row ">
                    <input type="hidden" name="action">
                    <div class="col-md-6  my-4">
                        <label for="names" >暱稱：</label>
                        <input class="rounded-3 form-control" type="text" id="names" name="names" required>
                    </div>
                    <div class="col-md-6 my-4">
                        <label for="title">公司：</label>
                        <input class="rounded-3 form-control" type="text" id="title" name="title" required>
                    </div>
                   
                    <div class="my-4">
                        <label for="good">優點：</label>
                        <textarea id="good" name="good" class="form-control" required></textarea>
                    </div>
                    <div class="my-4">
                        <label for="bad">缺點：</label>
                        <textarea id="bad" name="bad" class="form-control" required></textarea>
                    </div> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-danger">送出</button>
            </div>
            </div>
        </div>
        </div>
    </form>        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body>
</html>