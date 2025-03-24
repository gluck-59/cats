<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Мявк!</title>

<!--        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.1/dist/bootstrap-table.min.css">-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<!--        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">-->


        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
        <link rel="stylesheet" href="css/main.css">

        <link rel="icon" type="image/png" href="favicon.png" />
    </head>
    <body>
        <div class="container my-5">
            <div class="p-3 jumbotron rounded-3">
                <center>
                    <img src="cat.png" alt="cats">
                    <h1 class="">Учёт кошек</h1>
                    <p class="mx-auto fs-5 1text-muted">
                        Исходя из ТЗ и <a href="https://career.habr.com/vacancies/1000155616" target="_blank">описания вакансии</a> предлагаю договориться о следующем:
                    </p>
                </center>
                <div class="row justify-content-md-center">
                    <div class="col col-lg-6">
                        <ul class="text-left">
                            <li>фреймворки, композеры, докеры и т.д. не используем</li>
                            <li>дизайн-верстка — стандартный бутстрап/JQ без красивостей</li>
                            <li>всё остальное на моё усмотрение</li>
                        </ul>
                    </div>
                    <div class="row ">
                        <div class="col">
                        <center class="small text-muted">
                            * Понятно что одну и ту же задачу можно сделать миллионом разных способов.<br>Выбранные здесь способы, подходы и принципы могут не совпадать с вашими предпочтениями как минимум потому что мне они неизвестны.<br>Это не проблема — в вашем проекте я приму ваши предпочтения.
                        </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- модал добавить -->
        <div class="modal fade" id="addNewUserModal" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить</h5>
                    </div>
                    <div class="modal-body">
                        <form id="add-user-form" class="p-2" novalidate>
                            <div class="row mb-3 gx-3">
                                <div class="col">
                                    <input tabindex="-1" type="text" name="fname" class="form-control form-control-lg" placeholder="Кличка" required>
                                    <div class="invalid-feedback">Кличка обязательна</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 my-auto">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input checked class="form-check-input" type="radio" name="gender" value="1">
                                            Кот</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="gender" value="2">
                                            Кошка</label>
                                    </div>
                                </div>

                                <div class="col-md-6 my-auto">
                                    <input type="date" name="birthDate" class="form-control form-control-lg" placeholder="Дата рожд" required>
                                    <div class="invalid-feedback">Дата обязательна</div>
                                </div>
                            </div>

                            <div class="row mb-3 my-auto">
                                <label>Родители</label>
                                <div class="col-md-6">
                                    <select name="mother" class="form-select mother" data-placeholder="Мать"></select>
                                </div>
                                <div class="col-md-6">
                                    <select multiple name="father[]" class="form-select father" data-placeholder="Отец"></select>
                                </div>
                            </div>
                            <p class="clearfix"></p>
                            <div class="mb-2">
                                <input type="submit" value="Добавить" class="btn btn-success btn-block btn-lg" id="add-user-btn">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- модал редактировать -->
        <div class="modal fade" id="editUserModal" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактирование</h5>
                    </div>
                    <div class="modal-body">
                        <form id="edit-user-form" class="p-2" novalidate>
                            <input type="hidden" name="id" id="id">
                            <div class="row mb-3 gx-3">
                                <div class="col">
                                    <input type="text" name="fname" id="fname" class="form-control form-control-lg" placeholder="Кличка" required tabindex="-1">
                                    <div class="invalid-feedback">Кличка обязательна</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6  my-auto">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="gender" id="gender1" value="1">
                                            Кот</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="gender" id="gender2" value="2">
                                            Кошка</label>
                                    </div>
                                </div>
                                <div class="col-md-6  my-auto">
                                    <input type="date" name="birthDate" id="birthDate" class="form-control form-control-lg" placeholder="Дата рожд" required>
                                    <div class="invalid-feedback">Дата обязательна</div>
                                </div>
                            </div>

                            <div class="row mb-3 my-auto">
                                <label>Родители</label>
                                <div class="col-md-6">
                                    <select name="mother" class="form-select mother" data-placeholder="Мать"></select>
                                </div>
                                <div class="col-md-6 my-auto">
                                    <select multiple name="father[]" class="form-select father" data-placeholder="Отец"></select>
                                </div>
                            </div>

                            <p class="clearfix"></p>

                            <div class="mb-3">
                                <input type="submit" value="Сохранить" class="btn btn-success btn-block btn-lg" id="edit-user-btn">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- основная стр -->
        <div class="container">
            <div class="row mt-4">
                <div class="col-lg-12 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-primary"></h4>
                    </div>
                    <div>
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addNewUserModal">Добавить</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <div id="showAlert"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table data-toggle="table" data-search="true" class="table table-bordered text-center">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th data-sortable="true" data-field="fmane">Кличка</th>
                                <th data-sortable="true" data-field="age">Возраст, лет</th>
                                <th data-sortable="true" data-field="gender">Пол</th>
                                <th>Отец</th>
                                <th>Мать</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>



        <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!--        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.1/dist/bootstrap-table.min.js"></script>-->



        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>

