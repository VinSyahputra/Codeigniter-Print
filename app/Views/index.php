<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Document</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">BOGA UMB</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" id="formUMB">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" name="date" id="date" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name">Queue No</label>
                                        <input type="text" name="queue" id="name" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email">Receipt No</label>
                                        <input type="email" name="email" id="email" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="phone">Order ID</label>
                                        <input type="text" name="phone" id="phone" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="phone">Collected By</label>
                                        <input type="text" name="phone" id="phone" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>




                            <table class="table table-bordered table-striped table-hover mt-3">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th width="1%" class="text-center">
                                            <i class="fas fa-trash-alt"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="containerItem">
                                    <tr>
                                        <td><input type="text" name="item[]" class="form-control form-control-sm"></td>
                                        <td><input type="number" name="quantity[]" class="form-control form-control-sm"></td>
                                        <td><input type="number" name="price[]" class="form-control form-control-sm"></td>
                                        <td width="1%" class="text-center">
                                            <span class="btn-delete btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="col-12">
                                <button type="button" class="btn btn-outline-primary w-100" id="addItem"><i class="fas fa-plus"></i> Add Item</button>
                            </div>
                            <div class="d-flex mt-3">
                                <button type="button" id="btn-print" class="btn btn-success ms-auto"><i class="fas fa-print"></i> Print</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#addItem').click(function() {
                addRowItem();
            });
            $('#btn-print').click(function(e) {
                $dataSend = new FormData($('#formUMB')[0]);
                let jsonObj = {};
                $dataSend.forEach(function(value, key) {
                    jsonObj[key] = value;
                });
                jsonObj.items = [];
                $('#containerItem tr').each(function() {
                    let item = {};
                    $(this).find('input').each(function() {
                        item[$(this).attr('name')] = $(this).val();
                    });
                    jsonObj.items.push(item);
                });
                delete jsonObj['item[]'];
                delete jsonObj['quantity[]'];
                delete jsonObj['price[]'];

                postData(jsonObj, '/print', (res) => {
                    if (res.respon) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Data has been saved',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Data failed to save',
                        });

                    }
                });
            })
        });


        const addRowItem = () => {
            // Create HTML for new row
            var newRow = `
                <tr>
                    <td><input type="text" name="item[]" class="form-control form-control-sm"></td>
                    <td><input type="number" name="quantity[]" class="form-control form-control-sm"></td>
                    <td><input type="number" name="price[]" class="form-control form-control-sm"></td>
                    <td width="1%" class="text-center">
                        <span class="btn-delete btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                    </td>
                </tr>
            `;

            $('#containerItem').append(newRow);


            $('.btn-delete').click(function() {
                $(this).closest('tr').remove();
            });
        }







        function postData(data, url, response, beforesend) {
            $.ajax({
                xhrFields: {
                    withCredentials: true
                },
                type: 'POST',
                contentType: "application/json",
                url: <?= json_encode(base_url()); ?> + url,
                data: JSON.stringify(data),
                beforeSend: beforesend,
                success: (res) => {
                    response(res);

                    if ($("#sidebar") && $("#sidebar").length > 0) {
                        setContentHeight();
                    }
                },
                error: (err) => {
                    errorSwal(err.status);
                    // errorCallbackMethod(err);
                },
            });
        }
    </script>
</body>

</html>