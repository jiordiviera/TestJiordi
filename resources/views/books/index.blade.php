<!doctype html>
<html lang="{{str_replace('-','_',app()->getLocale())}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .loading {
            display: none;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.js"></script>
    <title>{{config('app.name')}}</title>
</head>
<body>
<div class="container">
    <div>
        <div class="p-3">
            <h1 class="text-center">Welcome to {{ config('app.name') }}</h1>
            <p class="text-center">This is a simple Laravel application using Blade templating engine.</p>
        </div>
        <div class="d-flex justify-content-between">
            <h3>Manager book using this simple interface.</h3>
            <button class="btn btn-primary btn-sm" id="addBookBtn">Add Book</button>
        </div>
    </div>
    <div class="mt-5">
        <table id="dataTable" class="table table-striped">
            <thead>
            <tr>
                {{--
                {data: 'id'},
            {data: 'title'},
            {data: 'author'},
            {data: 'genre'},
            {data: 'status'},
            {data: 'price'},
            {data: 'quantity'},
            {data: 'images'},--}}
                <td>id</td>
                <td>Title</td>
                <td>Author</td>
                <td>GENRE</td>
                <td>Status</td>
                <td>Price</td>
                <td>QUANTITY</td>
                <td>Actions</td>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

</div>

<div class="modal fade" id="bookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter/Modifier un utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bookForm" enctype="multipart/form-data">
                    <input type="hidden" id="bookId" name="id">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title">
                        <div id="titleError" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="author" name="author">
                        <div id="authorError" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description">
                        <div id="descriptionError" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option value="1" selected>Available</option>
                            <option value="0">Borrowed</option>
                        </select>
                        <div id="statusError" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Publication year</label>
                        <input type="date" class="form-control" id="publication_year" name="publication_year">
                        <div id="publicationYearError" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        {{--
                          case DRAMATIC ='dramatic';
    case FANTASTIC = 'fantastic';
    case HORROR = 'horror';
    case ROMANCE = 'romance';
    case TRAGEDY = 'tragedy';
    case NONE = 'none';--}}
                        <label for="name" class="form-label">Genre</label>
                        <select type="text" class="form-control" id="genre" name="genre">
                            <option value="fantastic">FANTASTIC</option>
                            <option value="horror">HORROR</option>
                            <option value="dramatic">DRAMATIC</option>
                            <option value="romance">ROMANCE</option>
                            <option value="tragedy">TRAGEDY</option>
                            <option value="none">NONE</option>
                        </select>
                        <div id="genreError" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price">
                        <div id="priceError" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity">
                        <div id="quantityError" class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">Images</label>
                        <input type="file" class="form-control" id="photo" name="images" accept="image/*">
                        <div id="imagesError" class="invalid-feedback"></div>
                    </div>
                    <div id="photoPreview" class="mb-3"></div>
                    <div id="loadingUpload" class="loading text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveBook">Save</button>
                <div id="loadingSave" class="loading text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewBookModal" tabindex="-1" role="dialog" aria-labelledby="viewBookModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewBookModalLabel">Book Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="bookDetailsContent">
                <!-- Les détails du livre seront injectés ici par JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script src="{!! asset('js/utils.js') !!}"></script>
</body>
</html>
