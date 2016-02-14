<?php echo $form?>
<script language="javascript">

    $(document).ready(function() {
        $("#user_id").on("change", function(event) {

            // Get/check for extra form fields
            $.getJSON("/bend-lot/ajax_getcontact/" + $("#user_id").val(),
                function(result) {
                    $("#firstname").val(result.firstname);
                    $("#lastname").val(result.lastname);
                    $("#email").val(result.email);
                    $("#homephone").val(result.homephone);
                    $("#workphone").val(result.workphone);
                    $("#mobile").val(result.mobile);
                }
            );
        });
    });
</script>            

