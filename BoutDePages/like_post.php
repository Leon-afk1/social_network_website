<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<button type="button" id="likeButton" >Liker</button>

<script type="button/javascript">
    $(document).ready(function){
        $('likeButton').click(function(){
            $.ajax({
                type: 'post',
                url: 'like.php',
                data: {postId: 45},
                success: function(data){
                    $('#output').html(data);
                }
            });
        })
    }
</script>