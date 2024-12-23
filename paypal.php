

<?php require_once "dbcon.php"; ?>
<?php session_start(); ?>
<?php include "include/header.php" ?>




            <div id="paypal-button-container"></div>
                        <p id="result-message"></p>

                                                
                        <!-- Initialize the JS-SDK -->
                        <script
                        src="https://www.paypal.com/sdk/js?client-id=AVxlLc--yDX3B6XsB5gpaG1QLL-omxB0uFzot6xBwGCQcS5zv5BS37eqfQhEtGZGWHK0tJCLKnWOTXRF&buyer-country=US&currency=USD&components=buttons&enable-funding=venmo,paylater,card"
                        data-sdk-integration-source="developer-studio">
                        </script>
                        <script src="js/app.js"></script>


<?php include "include/footer.php" ?>