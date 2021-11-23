<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel Api With Axios</title>
    <!-- CSS only -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <style>
        body{
            padding-top:60px;
        }
    </style>
</head>
<body>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h4>Posts</h4>
                    <span id="successMsg"></span>
                    <table class="table table-bordered table-hover">

                        <thead>
                        <tr>
                            <td>Id</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody id="post">

                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <h4>Create Post</h4>
                    <span id="msg"></span>
                    <Form id="myForm">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="title">
                            <span id="titleErr" class="mb-2"></span>
                        </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="" class="form-control" rows="5"></textarea>
                                <span id="descErr" class="mb-2"></span>
                            </div>
                        <button type="submit" class="btn btn-primary w-100 mt-3">Submit</button>
                    </Form>
                </div>
            </div>
        </div>



        <!-- Modal -->

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Post</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form name="editForm" id="closeForm">
                        <div class="modal-body">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="title" required>
                                    <span id="titleErr" class="mb-2"></span>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="" class="form-control" rows="5" required></textarea>
                                    <span id="descErr" class="mb-2"></span>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>

        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

        {{--axios--}}
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            //Read
            var postData =  document.getElementById('post');
            var titleData = document.getElementsByClassName('titleData');
            var idData = document.getElementsByClassName('idData');
            var btnData = document.getElementsByClassName('btnData');
            var descData = document.getElementsByClassName('descData');
            axios.get('/api/posts')
                .then(res => {
                   res.data.posts.forEach(item => {
                        // console.log(item)
                       DisplayPost(item);
                   })
                })
                .catch(err=>console.log(err))
            //Crate
            var MyForm = document.forms['myForm'];
            var titleInput = MyForm['title'];
            var descriptionInput = MyForm['description'];
            MyForm.onsubmit = (e) => {
                e.preventDefault();
                axios.post('/api/posts',{
                    title:titleInput.value,
                    description:descriptionInput.value
                })
                     .then(res => {
                         console.log(res.data);
                         var msg = document.getElementById('msg');
                         if(res.data.msg == "Succssfully Inserted"){
                             msgAlert(res);
                             MyForm.reset();
                             DisplayPost(res.data.post);
                         }else{
                             $titleErr = document.getElementById('titleErr');
                             $descriptionErr = document.getElementById('descErr');
                             $titleErr.innerHTML = titleInput.value == '' ? '<i class="text-danger">' +res.data.msg.title+ '</i>' : '';
                             $descriptionErr.innerHTML = descriptionInput.value == '' ? '<i class="text-danger">' +res.data.msg.description+ '</i>' : '';
                         }
                     })
                     .catch((err) =>{
                         console.log(err.response);
                     })
            };
            //edit & Update
            var editForm = document.forms['editForm'];
            var editTitleForm = editForm['title'];
            var editDescForm = editForm['description'];
            var postIdToUpdate,oldValue;
            //EditForm
            function  editBtn(postId){

                postIdToUpdate = postId;
                axios.get('api/posts/' + postId)
                     .then(res => {
                         editTitleForm.value = oldValue = res.data.title;
                         editDescForm.value = res.data.description;
                     })
                     .catch(err => console.log(err))
            }
            //updateForm
            editForm.onsubmit = function (event){
                event.preventDefault();
                //console.log(postIdToUpdate)
                axios.put('api/posts/'+ postIdToUpdate,{
                    title:editTitleForm.value,
                    description: editDescForm.value,
                })
                .then(res  => {
                    console.log(res)
                    msgAlert(res);
                    $('#closeForm').modal('hide');
                    for(var i = 0; i < titleData.length;i++){
                        if(titleData[i].innerHTML == oldValue){
                            titleData[i].innerHTML = editTitleForm.value;
                            descData[i].innerHTML = editDescForm.value;
                        }
                    }
                })
                .catch(err => console.log(err))
            }
            //delete
            function deleteBtn(postId){
                if(confirm("Are you sure to delete?")) {
                    axios.delete('api/posts/' + postId)
                        .then(res => {
                            msgAlert(res)
                            for (var i = 0; i < titleData.length; i++) {
                                if (titleData[i].innerHTML == res.data.post.title) {
                                    idData[i].style.display = titleData[i].style.display =descData[i].style.display = btnData[i].style.display ='none';
                                }
                            }
                        })
                        .catch(err => console.log(err))
                }
            };
            //Helper Function
            function DisplayPost(res){
                postData.innerHTML += ' <tr><td class="idData">'+res.id+'</td> <td class="titleData">'+res.title+'</td> <td class="descData">'+res.description+'</td> <td class="btnData"> <button class="btn btn-success btn-sm"  data-toggle="modal" data-target="#exampleModal" onclick="editBtn('+res.id +')" >Edit</button> <button class="btn btn-danger btn-sm" onclick="deleteBtn('+res.id+')">Delete</button> </td> </tr>'
            }
            function msgAlert(res){
                document.getElementById('successMsg').innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert"> <strong>'+res.data.msg+'</strong>  <button type="button" class="close" data-dismiss="alert" aria-label="Close" > <span aria-hidden="true">&times;</span></button> </div>';
            }

        </script>

</body>
</html>
