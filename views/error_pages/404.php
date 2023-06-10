<?php
//Head
require_once ("views/partials/head.php");
?>
<!--Made by purplexmoss (https://freefrontend.com/html-css-404-page-templates/)-->
<main class="error">
    <div class="mainbox">
        <div class="msg404">
            <span class="err">4</span>
            <i class="far fa-question-circle fa-spin"></i>
            <span class="err2">4</span>
        </div>
        <div class="msg">Maybe this page moved? Got deleted? Is hiding out in quarantine? Never existed in the first place?
            <p>Let's go 
                <a class="errorlink" href="<?php echo root;?>">home</a> and try from there.
            </p>
        </div>
    </div>
</main>
<?php
//Footer
require_once ("views/partials/footer.php");
?>