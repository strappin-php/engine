<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/functions.php';

$heading = 'Functional Tests';
?>
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $heading ?> | Strappin' Tests</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9"
        crossorigin="anonymous"
    >

    <link rel="stylesheet" href="./index.css">
</head>

<body>

    <div class="container">
        <h1 class="visually-hidden"><?= $heading ?></h1>

        <div class="row">
            <div class="col-2">

                <div class="sticky-top">
                    <nav class="h-100 flex-column align-items-stretch pe-4">
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link" href="#navs-tabs">Navs &amp; Tabs</a>
                            <nav class="nav nav-pills flex-column">
                                <a class="nav-link ms-3 my-1" href="#tabbed-interface">Tabbed Interface</a>
                            </nav>
                        </nav>
                    </nav>
                </div>

            </div>

            <div class="col-10">

                <h2 id="navs-tabs">Navs &amp; Tabs</h2>

                <h3 id="tabbed-interface">Tabbed Interface</h3>

                <div class="accordion" id="example-tabbed-interface">
                    <div class="accordion-item func-test">
                        <h4 class="accordion-header">
                            <button
                                class="accordion-button"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#tabbed-interface__optimal"
                                aria-expanded="true"
                                aria-controls="tabbed-interface__optimal"
                            >Optimal</button>
                        </h4>

                        <div id="tabbed-interface__optimal" class="accordion-collapse collapse show" data-bs-parent="#example-tabbed-interface">
                            <div class="accordion-body">
                                <?= execTest(__DIR__ . '/NavsTabs/tabbed_interface/optimal.php') ?>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item func-test">
                        <h4 class="accordion-header">
                            <button
                                class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#tabbed-interface__verbose"
                                aria-expanded="false"
                                aria-controls="tabbed-interface__verbose"
                            >Verbose</button>
                        </h4>

                        <div id="tabbed-interface__verbose" class="accordion-collapse collapse" data-bs-parent="#example-tabbed-interface">
                            <div class="accordion-body">
                                <?= execTest(__DIR__ . '/NavsTabs/tabbed_interface/verbose.php') ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"
    ></script>

</body>
</html>
