$(document).ready(function () {
    // Configuration AJAX pour le jeton CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialisation de DataTable
    let table = new DataTable('#dataTable', {
        ajax: {
            url: '/api/books',
            dataSrc: ''
        },
        columns: [
            {data: 'id'},
            {data: 'title'},
            {data: 'author'},
            {data: 'genre'},
            {
                data: 'status',
                render: function (data) {
                    return data ? 'Available' : 'Borrowed';
                }
            },
            {data: 'price'},
            {data: 'quantity'},
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-primary view-book" data-id="${row.id}">View</button>
                        <button class="btn btn-sm btn-primary edit-book" data-id="${row.id}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-book" data-id="${row.id}">Delete</button>
                    `;
                }
            }
        ]
    });

    // Gestionnaires d'événements
    $("#addBookBtn").click(showAddBookModal); // Ajoute un livre
    $("#dataTable").on("click", ".view-book", handleViewBook); // Affiche les détails du livre
    $("#dataTable").on("click", ".edit-book", handleEditBook); // Modifie les informations du livre
    $("#saveBook").click(handleSaveBook); // Enregistre un livre
    $("#dataTable").on("click", ".delete-book", handleDeleteBook); // Supprime un livre
    $("#photo").change(handlePhotoChange); // Gère le changement de photo
    $("#bookModal").on("hidden.bs.modal", resetForm); // Réinitialise le formulaire à la fermeture du modal

    // Fonctions

    // Affiche le modal pour ajouter un livre
    function showAddBookModal() {
        resetForm();
        showModal('Add a book');
    }

    // Gère l'affichage des détails d'un livre
    function handleViewBook() {
        let id = $(this).data("id");
        fetchBookDetails(id);
    }

    // Gère la modification d'un livre
    function handleEditBook() {
        let id = $(this).data("id");
        fetchBookData(id);
    }

    // Gère l'enregistrement ou la mise à jour d'un livre
    function handleSaveBook(e) {
        e.preventDefault();
        let formData = new FormData($("#bookForm")[0]);
        let id = $("#bookId").val();
        let url = id ? `/api/books/${id}` : "/api/books";
        let method = id ? "PUT" : "POST"; // Utilise PUT pour la mise à jour

        sendBookData(url, method, formData);
    }

    // Envoie les données d'un livre via AJAX
    function sendBookData(url, method, formData) {
        $("#saveBook")
            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...')
            .prop("disabled", true);

        $.ajax({
            url: url,
            method: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $("#bookModal").modal("hide");
                table.ajax.reload();
                resetForm();
                alert("Book " + ($("#bookId").val() ? "updated" : "added") + " successfully!");
            },
            error: function (xhr) {
                console.error("Error saving book:", xhr.responseText);
                let response = JSON.parse(xhr.responseText);
                if (response.errors) {
                    displayErrors(response.errors);
                } else {
                    alert(response.message || "An unexpected error occurred.");
                }
            },
            complete: function () {
                $("#saveBook").html("Save").prop("disabled", false);
            }
        });
    }

    // Gère la demande de suppression d'un livre
    function handleDeleteBook() {
        if (confirm("Are you sure you want to delete this book?")) {
            let id = $(this).data("id");
            deleteBook(id);
        }
    }

    // Gère le changement de photo du livre
    function handlePhotoChange() {
        let file = this.files[0];
        if (file) {
            previewPhoto(file);
        } else {
            $('#photoPreview').html('');
        }
    }

    // Récupère les détails d'un livre pour affichage
    function fetchBookDetails(id) {
        $.ajax({
            url: `/api/books/${id}`,
            method: "GET",
            success: function (book) {
                showBookDetails(book);
                // showModal("View Book Details"); // Affiche le modal avec les détails du livre
            },
            error: function (xhr) {
                console.error("Error fetching book details:", xhr.responseText);
            }
        });
    }

    // Récupère les données d'un livre pour modification
    function fetchBookData(id) {
        $.ajax({
            url: `/api/books/${id}`,
            method: "GET",
            success: function (book) {
                populateForm(book);
                showModal("Edit a book"); // Affiche le modal pour éditer les informations du livre
            },
            error: function (xhr) {
                console.error("Error fetching book data:", xhr.responseText);
            }
        });
    }

    // Supprime un livre
    function deleteBook(id) {
        $.ajax({
            url: `/api/books/${id}`,
            method: "DELETE",
            success: function () {
                table.ajax.reload();
            },
            error: function (xhr) {
                console.error("Error deleting book:", xhr.responseText);
            }
        });
    }

    // Prévisualise l'image du livre
    function previewPhoto(file) {
        $('#loadingUpload').show();
        let reader = new FileReader();
        reader.onload = function (e) {
            $('#photoPreview').html(`<img src="${e.target.result}" style="max-width: 200px;">`);
            $('#loadingUpload').hide();
        }
        reader.readAsDataURL(file);
    }

    // Remplit le formulaire avec les données du livre
    function populateForm(book) {
        $("#bookId").val(book.id);
        $("#title").val(book.title);
        $("#author").val(book.author);
        $("#description").val(book.description);
        $("#status").val(book.status ? "1" : "0");
        $("#publication_year").val(book.publication_year);
        $("#genre").val(book.genre);
        $("#price").val(book.price);
        $("#quantity").val(book.quantity);
        $("#photoPreview").html(book.images ? `<img src="${book.images}" style="max-width: 200px;">` : "");
    }

    // Affiche les erreurs de validation dans le formulaire
    function displayErrors(errors) {
        resetFormErrors();
        Object.keys(errors).forEach(key => {
            $(`#${key}Error`).text(errors[key][0]).addClass('d-block');
        });
    }

    // Réinitialise les erreurs de validation dans le formulaire
    function resetFormErrors() {
        $('.invalid-feedback').text('').removeClass('d-block');
    }

    // Réinitialise le formulaire à son état initial
    function resetForm() {
        $("#bookForm")[0].reset();
        $("#bookId").val("");
        $("#photoPreview").html("");
        $("#loadingUpload").hide();
        resetFormErrors();
    }

    // Affiche le modal avec un titre spécifique
    function showModal(title) {
        $(".modal-title").text(title);
        $("#bookModal").modal("show");
    }

    // Affiche les détails du livre dans le modal de visualisation
    function showBookDetails(book) {
        let details = `
            <p><strong>Title:</strong> ${book.title}</p>
            <p><strong>Author:</strong> ${book.author}</p>
            <p><strong>Description:</strong> ${book.description}</p>
            <p><strong>Status:</strong> ${book.status ? 'Available' : 'Borrowed'}</p>
            <p><strong>Publication Year:</strong> ${book.publication_year}</p>
            <p><strong>Genre:</strong> ${book.genre}</p>
            <p><strong>Price:</strong> ${book.price}</p>
            <p><strong>Quantity:</strong> ${book.quantity}</p>
            <p><strong>Images:</strong></p>
            ${book.images ? `<img src="${book.images}" style="max-width: 200px;">` : ''}
        `;
        $("#bookDetailsContent").html(details);
        $("#viewBookModal").modal("show"); // Affiche le modal de visualisation des détails du livre
    }
});
