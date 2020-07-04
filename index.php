<?php

session_start();

require_once "config/config.php";
require_once "header.php";
require_once "baseView.php";
require_once "inspire.php";
require_once "components/utils.php";
require_once "entity/delete.php";

if (isset($_POST['item_id'])) {
    $_SESSION['item_id'] = $_POST['item_id'];
}

if (isset($_POST['delete'])) {
    removeItem($_SESSION['item_id']);
} elseif (isset($_POST['cancel'])) {
    header("Location: ".BASE_URL);
    return;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>TYDYSHKA</title>
    </head>
    <body>
        <script>
            function htmlentities(str) {
                return $("<div/>").text(str).html();
            }
        </script>
        <div class="container">
            <?php

            getResult();

            if ( !isset($_SESSION['user_id']) ) {
                echo '
                    <div class="quote">
                        <div class="alert alert-info" role="alert">
                            You should <a href="'.BASE_URL.'account/login">Log In</a> or <a href="'.BASE_URL.'account/register">Create a New Account</a> in order to access all the site features
                        </div>
                        <p>Here\'s an inspiring quote, which would make your day brighter:</p><b>';

                echo (new Inspire)->getQuote();

                echo '</b></div>
                    <div id="accordion" class="fixed-bottom">
                      <div class="card">
                        <div class="card-header" id="headingOne">
                          <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                              Demo Table View (showing data from the test account - l/p: test@example.com
                            </button>
                          </h5>
                        </div>
                    
                        <div id="collapseOne" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion">
                          <div class="card-body">
                            <table class="table table-hover" hidden id="testTable">
                                <tr>
                                    <td><b>#</b></td>
                                    <td><b>Headline</b></td>
                                    <td><b>Priority</b></td>
                                    <td><b>Deadline</b></td>
                                </tr>
                                <tbody id="testData">    
                                </tbody>
                            </table>
                    ';

                echo('
                    <script type="text/javascript">
                        $.getJSON("getJson", function(rows) {
                            
                            $("#testData").empty();
                            
                            rows.length > 0 ? $("#testTable").removeAttr("hidden"): "";
                            
                            for (let i = 0; i < rows.length; i++) {
                                
                                let row = rows[i];
                               
                                $("#testData").append("<tr><td>"+(i+1)+"</td><td>"
                                    + "<a href=\\"entity/view?item_id="+htmlentities(row.item_id)+"\\">"+htmlentities(row.headline)
                                    + "</td><td>"
                                    + htmlentities(row.priority)
                                    + "</td><td>"
                                    + htmlentities(row.deadline)
                                    + "</td></tr>");
                            }
                        });       
                    </script>        
                ');
                echo '
                          </div>
                        </div>
                      </div>
                    </div>
                ';

            } elseif ( isset($_SESSION['user_id']) ) {
                echo('
                    <table class="table table-hover" hidden id="mainTable">
                    <tr>
                        <td><b>#</b></td>
                        <td><b>Headline</b></td>
                        <td><b>Priority</b></td>
                        <td><b>Deadline</b></td>
                        <td><b>Action</b></td>
                    </tr>
                    <tbody id="data">    
                    </tbody>
                    </table>
                ');

                echo('
                    <script>
                        function getId(i) {
                            let loc = "#deleteConfirm"+i;
                            let item_id = $(loc).attr("item_id");
                            $.ajax({
                                type: "post",
                                data: {
                                    "item_id": item_id
                                }
                            });
                            
                            return item_id;
                        };   
                    </script>
                    
                    <script type="text/javascript">
                        let found = false;
                        let removeId = null;
                        
                        $.getJSON("getJson", function(rows) {
                            found = rows.length > 0;
                            $("#data").empty();
                            
                            found ? $("#mainTable").removeAttr("hidden"): "";
                            
                            for (let i = 0; i < rows.length; i++) {
                                
                                row = rows[i];
                                window.console && console.log(\'Row: \'+i+\' \'+row.headline);
                                $("#data").append("<tr><td>"+(i+1)+"</td><td>"
                                    + "<a href=\\"entity/view?item_id="+htmlentities(row.item_id)+"\\">"+htmlentities(row.headline)
                                    + "</td><td>"
                                    + htmlentities(row.priority)
                                    + "</td><td>"
                                    + htmlentities(row.deadline)
                                    + "</td><td>"
                                    + "<a class=\\"btn btn-outline-primary\\" href=\\"entity/edit?item_id="+row.item_id+"\\">Edit</a> "
                                    + "<button type=\\"button\\" name=\\"deleteConfirm\\" id=\\"deleteConfirm"+i+"\\" onclick=\\"return getId("+i+");\\" data-toggle=\\"modal\\" data-target=\\"#deleteModal\\" item_id=\\""+row.item_id+"\\" class=\\"btn btn-outline-danger\\">Delete</a>"
                                    + "</td></tr>");
                            }
                            if ( !found ) {
                                $(".container").append("<div class=\\"alert alert-warning\\" role=\\"alert\\">Hm... Nothing was added yet.</div>");
                            }
                        });
                           
                    </script>        
                ');
            }
            ?>
        </div>
    </body>
</html>