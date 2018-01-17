<?php
require "vendor/autoload.php";
use Symfony\Component\Yaml\Yaml;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();


$sitesFile = __DIR__."/sites.yml";
if (!file_exists($sitesFile)) {
    $createFile = fopen($sitesFile, 'w') or die("Cannot create file: " . $sitesFile);
}
$sites = Yaml::parseFile($sitesFile);

if (isset($_POST['site'], $_POST['type'])) {
    array_push($sites, ['name' => $_POST['site'], 'type' => $_POST['type']]);
    $yaml = Yaml::dump($sites);
    file_put_contents($sitesFile, $yaml);
}
?>
    <!doctype html>
    <html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy"
            crossorigin="anonymous">
        <style>
            .ace_editor {
                height: 400px;
            }
        </style>
        <title>Environment Manager</title>
    </head>

    <body class="pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-5">
                    <form method="post">
                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control" name="site" placeholder="Site name">
                            </div>
                            <div class="col">
                                <select name="type" class="form-control">
                                    <option value="Laravel">Laravel</option>
                                </select>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <div class="card-group">
                        <div class="card">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Project Type</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <?php
                                if ($sites) {
                                    foreach ($sites as $site) {
                                        echo "<tr>";
                                        echo '<td>'.$site['name'] . "</td>";
                                        echo '<td>'.@$site['type'] . "</td>";
                                        echo '<td><small><a href="?site='.$site['name'].'" class="btn btn-link btn-sm text-uppercase">View .env</a></small></td>';
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </table>
                        </div>
                        <?php if (isset($_GET['site'])): ?>
                        <div class="card">
                            <div class="card-body">
                                <form method="post">
                                    <textarea name="env" id="env" rows="20" class="form-control"><?php echo file_get_contents(getenv("homestead") . "/" . $_GET['site'] . "/.env"); ?></textarea>
                                    <div class="text-right mt-3">
                                        <a href="<?php echo parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>" class="btn btn-outline-secondary">Close</a>
                                        <button type="submit" class="btn btn-primary">Save .env</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.9/ace.js"></script>
        <script>
            var editor = ace.edit("env");
            editor.setTheme("ace/theme/monokai");
            editor.getSession().setMode("ace/mode/text");
        </script>
    </body>

    </html>