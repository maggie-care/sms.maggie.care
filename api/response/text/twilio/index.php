<?php
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
    <Message>Hello, Mobile Monkey <?php var_dump( $_POST );?></Message>
</Response>