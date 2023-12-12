<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Task Crosspoles</title>
        
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md">
                    <form id="frm">
                        <div class="row">
                            <div class="col-md-6">
                                
                                <div id="error_panel"></div>
                                
                                <div class="form-group">
                                    <label for="name">Profile Image</label>
                                    <input id="profile_image" type="file" name="profile_image" class="form-control"  />
                                </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input id="name" type="text" name="name" class="form-control"  />
                                    
                                </div>
                                <div class="form-group">
                                    <label for="name">Email</label>
                                    <input id="email" type="text" name="email" class="form-control"  />
                                    
                                </div>
                                <div class="form-group">
                                    <label for="duty">duty</label>                            
                                    <select class="form-control" name="role_id[]" id="role_dd" multiple >
                                        @foreach ($roles as $item)
                                                <option value="{{$item->id}}">{{$item->role_name}}</option>                         
                                        @endforeach
                                    </select>
                                    
                                </div>
                            </div>

                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Phone</label>
                                    <div>
                                        <label id="code_label" style="background-color: darkgoldenrod;padding:7px 3px;">+91</label>
                                        <input id="phone" type="number" name="phone" class="form-control" style="margin-top:-46px;padding-left:40px;"  />
                                    </div>
                                    
                                </div>
                                <div class="form-group">
                                    <label for="name">Description</label>
                                    <textarea id="desc" rows="7" cols="10" name="description" class="form-control" ></textarea>
                                </div>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="" id="" class="btn btn-success btn-md" value="Register" />
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md">
                    <table class="table">
                        <thead class="thead-dark">
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Description</th>
                            <th scope="col">Roles</th>
                          </tr>
                        </thead>
                        <tbody id="table_content">
                          
                        </tbody>
                      </table>
                      
                      
                </div>
            </div>
        </div>

        <!-- jQuery first, then Popper.js, and then Bootstrap's JavaScript -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            let error = 0;
            var error_arr = [];
            $(document).ready(function() {
                ajax_load_users();
                $('#role_dd').select2();
                let formdata = null;

                $('#phone').on('input',function(evt){
                    let word = $('#phone').val();
                    if(word.length > 10){
                        $('#error_panel').html('');
                        error_arr = [];                   
                        error_arr.push('phone should be only 10 digits');
                        error_arr.forEach(element => {
                            let html = '<div class="alert alert-danger">'+element+'</div>';
                            $('#error_panel').append(html);
                        });
                        showMessage();
                    }
                    // console.log(word);
                });

                $('#frm').submit(function(evt){
                    evt.preventDefault();

                    let name = $('#name').val().trim();
                    let email = $('#email').val();
                    let phone = $('#phone').val();
                    let role_dd = $('#role_dd')[0];
                    let desc = $('#desc').val();
                    let profile_image = $('#profile_image');

                    error_arr = [];
                    $('#error_panel').html('');
                    // $('#error_panel').fadeOut();

                    if(phone.indexOf("+91") == 0){
                        phone = phone.substr(3);
                    }
                    
                    
                    // console.log(new FormData(this));
                    formdata = new FormData(this);
                
                    if(profile_image[0].files.length == 0){
                        error_arr.push('select an image');
                    }
                    if(name.length == 0 || name == null || name == ''){
                        error_arr.push('enter name');
                    }
                    if(email.length == 0 || email == null || email == ''){
                        error_arr.push('enter email');
                    }
                    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                    if(!email.match(mailformat)){
                        error_arr.push('enter correct email');
                        $('#email').focus();
                    }
                    if(phone.length == 0 || phone == null || phone == ''){
                        error_arr.push('enter phone');
                    }
                    if(desc.length == 0 || desc == null || desc == ''){
                        error_arr.push('enter description');
                    }
                    if(phone.length != 10){
                        error_arr.push('enter 10 digits phone');
                    }
                    if(role_dd.selectedOptions.length == 0){
                        error_arr.push('select a role');
                    }


                    if(error_arr.length > 0){
                        error_arr.forEach(element => {
                            let html = '<div class="alert alert-danger">'+element+'</div>';
                            $('#error_panel').append(html);
                        });
                        showMessage();
                    }
                    else{
                        ajax_register(formdata);
                    }
                    
                });
            });


            function showMessage(){
                $('#error_panel').show();
                setTimeout(() => {
                    // $('#error_panel').fadeOut();                            
                }, 2000);
            }

            function ajax_register(fd){                
                $.ajax({
                    url: '{{ route('register') }}',
                    type: 'POST',
                    data: fd,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(data){
                        console.log(data);
                        if(!data.status){
                            data.msg.forEach(element => {
                                let html = '<div class="alert alert-danger">'+element+'</div>';
                                $('#error_panel').append(html);
                            });
                            showMessage();
                        }
                        else{
                            let html = '<div class="alert alert-success">'+data.msg+'</div>';
                            $('#error_panel').append(html);
                            showMessage();
                        }
                        ajax_load_users();
                    },
                    error: function(err){
                        console.error(err);
                    }
                });
            }

            function ajax_load_users(){  
                $('#table_content').empty();
                let html = "<tr rowspan='6'><td>Loading...</td></tr>";
                $('#table_content').append(html);

                $.ajax({
                    url: '{{ route('load_users') }}',
                    type: 'GET',
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(data){
                        console.log(data);
                        if(!data.status){
                        }
                        else{
                            let roles = '';
                            
                            $('#table_content').empty();
                            for(var i=0;i<data.msg.length;i++){
                                for(var j=0;j<data.msg[i].roles.length;j++){
                                    if(j!=0){
                                        roles += "," + data.msg[i].roles[j].role_name;
                                    }
                                    else{
                                        roles += data.msg[i].roles[j].role_name;
                                    }
                                }
                                let html = '<tr>'+
                                                '<td>'+(i+1)+'</td>'+
                                                '<td>'+data.msg[i].name+'</td>'+
                                                '<td>'+data.msg[i].email+'</td>'+
                                                '<td>'+data.msg[i].phone+'</td>'+
                                                '<td>'+data.msg[i].description+'</td>'+
                                                '<td>'+roles+'</td>'+
                                            '</tr>';
                                $('#table_content').append(html);
                                roles = '';
                            }
                        }
                    },
                    error: function(err){
                        console.error(err);
                    }
                });
            }
        </script>
    </body>
</html>
