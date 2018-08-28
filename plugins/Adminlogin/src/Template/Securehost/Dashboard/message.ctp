<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
<?php
$this->Breadcrumbs->addCrumb('State Licence List', ['controller' => 'StateLicences', 'action' => 'index']);
$this->Breadcrumbs->addCrumb($heading);
?>

<div class="row messages-container">
    <div class="message-main-div">
        <div class="message-sender-list">
            <div class="search-left">
                <input type="text" placeholder="Search " id="searchUser" />
                <div class="cl"></div>
            </div>
            
            <div class="message-scroll mCustomScrollbar users-list" style="overflow-y: auto; height:850px;">
                
            </div>
            
            
        </div>
        <div class="right-container ">
            <div class="message-scroll mCustomScrollbar chat" style="overflow-y: auto; height:800px;">
                
            </div>
            <div class="textarea-btm">
                <textarea id="message" placeholder="Type your message here"></textarea>

                       <label id="attached" class="attach-images" onclick="$('#attachedWindow').show();">
                        <span class="choose-btn"></span>
                        <!--span id="file-name" class="file-box"></span-->
                        <span class="file-button upload-banner">
                        
                        </span>
                    </label>
                <input type="submit" data-imageUrl ='<?= SITE_URL.DS.'uploads'.DS.'chat/'; ?>' data-site-url="<?= SITE_URL; ?>" id="send" value="Send" />
                      
                
                
               
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div id="attachedWindow" class="modal">
        <div class="modal-content small-box login">
            <form action="" class="form-section success">
                <div class="form-group">
                    <div class="chat-box">
                            <form>
                                <div class="input-group">
                                    <span class="input-group-addon">										
                                        <label class="btn btn-default btn-xs">
                                             <input type="file" id="fileinput" multiple value="Upload Images" style="display:block">
                                        </label>										
                                    </span>
                                </div>
                            </form>
                        <div id="reviewImg" class="attachimages-new"></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="btn-group">
                        <input type="button" value="Ok" onclick="$('#attachedWindow').hide();" class="btn btn-primary small-btn">
                        &nbsp;&nbsp;&nbsp;<button class="btn btn-primary small-btn" onclick="$('#attachedWindow').hide();">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->Html->css('/frontend/css/jquery.mCustomScrollbar'); ?>
<?= $this->Html->script('/frontend/js/jquery.mCustomScrollbar',['type'=>"text/javascript"]);?> 

<input type="hidden" name="role_id" id="role_id" value="<?= $users['role_id'] ?>" />
<script src="//www.gstatic.com/firebasejs/3.9.0/firebase.js"></script>
<script src="//www.gstatic.com/firebasejs/3.9.0/firebase-app.js"></script>
<script src="//www.gstatic.com/firebasejs/3.9.0/firebase-database.js"></script>
<?= $this->Html->script('/frontend/js/config'); ?>
<?= $this->Html->script('/frontend/js/chat_realtime'); ?>
<?= $this->Html->css('Adminlogin.sweetalert'); ?>
<?= $this->Html->script('Adminlogin.sweetalert.min'); ?>
<script>
    var apis = '<?= $this->url->build(['controller' => $controller, 'action' => 'ajaxChat']); ?>';
    var imageDir = '';
    $(function () {
        var userlogin = false;

        // cek user	session
        $.ajax({
            url: apis,
            data: {
                data: 'cek'
            },
            type: "post",
            crossDomain: true,
            dataType: 'json',
            success: function (a) {
                if (a.status == 'success')
                {
                    var x = new Date(),
                            b = x.getDate(),
                            c = (x.getMonth() + 1),
                            d = x.getFullYear(),
                            e = x.getHours(),
                            f = x.getMinutes(),
                            g = x.getSeconds(),
                            date = d + '-' + (c < 10 ? '0' + c : c) + '-' + (b < 10 ? '0' + b : b) + ' ' + (e < 10 ? '0' + e : e) + ':' + (f < 10 ? '0' + f : f) + ':' + (g < 10 ? '0' + g : g);
                    var h = {
                        name: a.user,
                        avatar: a.avatar,
                        login: date,
                        user_id:a.user_id,
                        role_id : a.role_id,
                        tipe: 'login'
                    };
                    
                    userRef.push(h);
                    $('#login').hide();
                    $('#main').show();
                    //document.querySelector('#avatarlogin').src = a.avatar;
                    userlogin = a.user;
                    //$('#userlogin').html(a.user);
                    //document.querySelector('#public strong').innerHTML = a.user;
                    //document.querySelector('#public img').src = a.avatar;
                    chat_realtime(userRef, messageRef, apis, a.user_id, a.avatar, imageDir,a.user, null)
                }
            }
        });

        // user login
        $('#loginform').submit(function (e) {
            e.preventDefault();
            $('#status').html('<center>Wait...</center>');
            var i = $('#username').val(),
                    avatar = $('#avatar').val();
            if (i != '' && avatar != '') {
                $.ajax({
                    url: apis,
                    data: {
                        data: 'login',
                        name: i,
                        avatar: avatar
                    },
                    type: "post",
                    crossDomain: true,
                    dataType: 'json',
                    success: function (a) {
                        if (a.status == 'success') {
                            var x = new Date(),
                                    b = x.getDate(),
                                    c = (x.getMonth() + 1),
                                    d = x.getFullYear(),
                                    e = x.getHours(),
                                    f = x.getMinutes(),
                                    g = x.getSeconds(),
                                    date = d + '-' + (c < 10 ? '0' + c : c) + '-' + (b < 10 ? '0' + b : b) + ' ' + (e < 10 ? '0' + e : e) + ':' + (f < 10 ? '0' + f : f) + ':' + (g < 10 ? '0' + g : g);
                            var h = {
                                name: i,
                                avatar: avatar,
                                login: date,
                                tipe: 'login'
                            };
                            userRef.push(h);
                            window.location.reload()
                        } else {
                            $('#status').html("<div class='alert alert-danger'>Username sudah di pakai.</div>")
                        }
                    }
                })
            } else {
                alert('Form input ada yang belum di isi')
            }
        });

        // user logout
        $('#logout').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: apis,
                data: {
                    data: 'logout'
                },
                type: "post",
                crossDomain: true,
                dataType: 'json',
                success: function (a) {
                    if (a.status == 'success') {
                        var b = {
                            name: userlogin,
                            tipe: 'logout'
                        };
                        userRef.push(b);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    }
                }
            })
        });
         
        $("#searchUser").autocomplete({
            minLength: 0,
            source: function(request, response) {
            $.ajax({
                    url: '<?= $this->Url->build(['controller' => $controller, 'action' => 'ajaxChat'], true);?>',
                    type:'post',
                    data: {
                        data : 'autoComplete',
                        nickname:$("#searchUser").val()
                    },
                    success: function(a) {
                        var b = '';
                        $.each(JSON.parse(a), function (i, a) {
                            b += '<div id="' + a.id + '" data-is_flaged = "'+a.is_flaged+'" data-is_blocked = "'+a.is_blocked+'" data-name="' + a.name + '" data-name_id = "'+a.name_id+'" data-key_id = "'+a.key_id+'" class="chat-user user bounceInDown" data-tipe="users">';
                                b += ' <div class="pic">';
                                b +=    ' <img style="height:60px;width:60px;" src="' + a.avatar + '" alt="' + a.name + '" />';
                                b += ' </div>';
                                b += ' <div class="details">'
                                //b +=    ' <a class="remove" href="#"><img src="../images/remove-chat-icon.png" alt="remove" /></a>';
                                //b +=    ' <a class="flag" href="#"><img src="../images/flag-chat-icon.png" alt="flag" /></a>';
                                if(a.login)
                                {
                                    b +=    ' <p class="time text-muted">'+  timing(new Date(a.login)) +'</p>';
                                }
                                else
                                {
                                    b +=    ' <p class="time text-muted"></p>';
                                }
                                b +=    ' <h3>'+a.name+'</h3>';
                                b +=    ' <i class="fa fa-circle ' + (a.status == 'online' ? 'online' : 'offline') + '"></i>';
                                b +=    ' <p class="text last-message msg"></p>';
                                b +=    ' <small class="chat-alert label label-primary">0</small>';
                                b += ' </div>';
                                b += ' <div class="cl"></div>';
                                b += '</div>';
                        });
                        $('.users-list').html(b);
                    }
                });
            
            },
        });
        
        // emojiPicker
       /* window.emojiPicker = new EmojiPicker({
            emojiable_selector: '[data-emojiable=true]',
            assetsPath: '//onesignal.github.io/emoji-picker/lib/img/',
            popupButtonClasses: 'fa fa-smile-o'
        });
        window.emojiPicker.discover();*/

    });
    $(document).ready(function(){
        // Admin user chat
        $(".migrate-support").on('click',function(e){
            e.preventDefault();
            var type = $(this).data('type');
            console.log(type);
            if($(this).text() == 'Migrate Support')
            {
                 $.ajax({
                    url: apis,
                    data: {
                        data: 'admin'
                    },
                    type: "post",
                    crossDomain: true,
                    dataType: 'json',
                    success: function (a) 
                    {
                        var b = '';
                        $.each(a, function (i, a) {
                            b += '<div id="' + a.id + '" class="chat-user user bounceInDown" data-tipe="users">';
                                b += ' <div class="pic">';
                                b +=    ' <img style="height:60px;width:60px;" src="' + a.avatar + '" alt="' + a.name + '" />';
                                b += ' </div>';
                                b += ' <div class="details">'
                                if(a.login)
                                {
                                    b +=    ' <p class="time text-muted">'+  timing(new Date(a.login)) +'</p>';
                                }
                                else
                                {
                                    b +=    ' <p class="time text-muted"></p>';
                                }
                                //b +=    ' <a class="remove" href="#"><img src="../images/remove-chat-icon.png" alt="remove" /></a>';
                                //b +=    ' <a class="flag" href="#"><img src="../images/flag-chat-icon.png" alt="flag" /></a>';
                                //b +=    ' <p class="time text-muted">'+ timing(new Date(a.login)) +'</p>';
                                b +=    ' <h3>'+a.name+'</h3>';
                                b +=    ' <i class="fa fa-circle ' + (a.status == 'online' ? 'online' : 'offline') + '"></i>';
                                b +=    ' <p class="text last-message msg"></p>';
                                b +=    ' <small class="chat-alert label label-primary">0</small>';
                                b += ' </div>';
                                b += ' <div class="cl"></div>';
                                b += '</div>';
                        });
                        $('.users-list').html(b);
                        $('.migrate-support').text('Chat User');
                        $('.migrate-support').attr('data-type',2);
                    }
                });
            }
            else
            {
                $('.migrate-support').attr('data-type',1);
                $('.migrate-support').text('Migrate Support');
                chat_realtime(userRef, messageRef, apis, a.user, a.avatar, imageDir)
            }
        });
        //attchaemend
    });
    function timing(a) {
        var s = Math.floor((new Date() - a) / 1000),
                i = Math.floor(s / 31536000);
        if (i > 1) {
            return i + " yrs ago"
        }
        i = Math.floor(s / 2592000);
        if (i > 1) {
            return i + " mon ago"
        }
        i = Math.floor(s / 86400);
        if (i > 1) {
            return i + " dys ago"
        }
        i = Math.floor(s / 3600);
        if (i > 1) {
            return i + " hrs ago"
        }
        i = Math.floor(s / 60);
        if (i > 1) {
            return i + " min ago"
        }
        return (Math.floor(s) > 0 ? Math.floor(s) + " sec ago" : "just now")
    }
    
</script>
