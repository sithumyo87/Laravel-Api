<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel Api With Axios</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    {{--axios--}}
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            //Read
            axios.get('/api/posts')
                .then(res => {
                   var postData =  document.getElementById('post');
                   res.data.forEach(item => {
                       postData.innerHTML += ' <tr><td>'+item.id+'</td> <td>'+item.title+'</td> <td>'+item.description+'</td> <td> <button class="btn btn-success btn-sm"  data-bs-toggle="modal" data-bs-target="#exampleModal">Edit</button> <button class="btn btn-danger btn-sm">Delete</button> </td> </tr>'
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
                         console.log(res.data.msg);
                         var msg = document.getElementById('msg');

                         if(res.data.msg == "Succssfully Inserted"){
                             msg.innerHTML = '<div class="alert alert-warning alert-dismissible fade show" role="alert"> <strong>'+res.data.msg+'</strong>  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> </div>'
                             MyForm.reset();
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




        </script>

</body>
</html>
