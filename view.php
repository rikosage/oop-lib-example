<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">

    <link rel="stylesheet" href="/res/master.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <form class="col s6" action = "/?action=find" method="post">
                <div class="row">
                    <h4>Select</h4>
                    <div class="input-field col s6">
                        <input type="email" name="email" id="select-email">
                        <label for="select-email">Input email here or leave as it</label>
                        <button type="submit" class="waves-effect waves-light btn">Search</a>
                    </div>
                    <div class="input-field col s6">
                        <h5>Search in:</h5>
                        <p>
                            <input type="checkbox" id="select-json" name="storage[json]"/>
                            <label for="select-json">Json</label>
                        </p>
                        <p>
                            <input type="checkbox" id="select-sqlite" name="storage[sqlite]"/>
                            <label for="select-sqlite">Sqlite</label>
                        </p>
                        <p>
                            <input type="checkbox" id="select-mysql" name="storage[mysql]"/>
                            <label for="select-mysql">Mysql</label>
                        </p>
                    </div>
                </div>
            </form>

            <form class="col s6" action = "/?action=insert" method="post">
                <div class="row">
                    <h4>Insert</h4>
                    <div class="col s6">
                        <div class="input-field">
                            <input type="email" required="required" name="insert[email]" id = "insert-email">
                            <label for="insert-email">Input email</label>
                        </div>
                        <div class="input-field">
                            <input type="text" required="required" name="insert[first_name]" id = "first_name">
                            <label for="first_name">Input first name</label>
                        </div>
                        <div class="input-field">
                            <input type="text" required="required" name="insert[last_name]" id = "last_name">
                            <label for="last_name">Input last name</label>
                        </div>

                        <button type="submit" class="waves-effect waves-light btn">Insert</a>
                    </div>
                    <div class="input-field col s6">
                        <h5>Search in:</h5>
                        <p>
                            <input type="checkbox" id="insert-json" name="storage[json]"/>
                            <label for="insert-json">Json</label>
                        </p>
                        <p>
                            <input type="checkbox" id="insert-sqlite" name="storage[sqlite]"/>
                            <label for="insert-sqlite">Sqlite</label>
                        </p>
                        <p>
                            <input type="checkbox" id="insert-mysql" name="storage[mysql]"/>
                            <label for="insert-mysql">Mysql</label>
                        </p>
                    </div>
                </div>
            </form>
        </div>
        <?php if ($request->result['find']): ?>
            <div class="row blue lighten-4">
                <div class="grey lighten-4">
                    <h3>Results of searching</h3>
                </div>
                <?php foreach ($request->result['find'] as $source => $type): ?>

                        <?php if ($type): ?>
                            <h5 class="source center">
                                Source:
                                <strong class="pink-text text-darken-3">
                                    <?= $source; ?>
                                </strong>
                            </h5>
                        <?php endif; ?>

                        <?php foreach ($type as $row): ?>
                        <div class="row">
                            <div class="col s3">
                                <strong>ID:</strong> <?= $row['id']; ?>
                            </div>
                            <div class="col s3">
                                <strong>First Name:</strong> <?= $row['first_name']; ?>
                            </div>
                            <div class="col s3">
                                <strong>last Name:</strong> <?= $row['last_name']; ?>
                            </div>
                            <div class="col s3">
                                <strong>Email:</strong> <?= $row['email']; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($request->result['insert']): ?>
            <h3 class="green lighten-3">SUCCESS!</h3>
        <?php endif; ?>

        <?php if ($error = $request->getError()): ?>
            <h3 class="red lighten-3"><?= $error; ?></h3>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
             Materialize.updateTextFields();
        });
    </script>
</body>
</html>
