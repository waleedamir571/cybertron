<?php include 'partials/header.php'; ?>
<?php
// ... existing code ...
require_once 'backend/config/dbc.php';
require_once 'backend/function/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$position = $id ? getJobPositionById($id, $connection) : null;

function renderListItems($jsonOrArray)
{
    $items = [];
    if (is_string($jsonOrArray) && $jsonOrArray !== '') {
        $decoded = json_decode($jsonOrArray, true);
        if (is_array($decoded))
            $items = $decoded;
    } elseif (is_array($jsonOrArray)) {
        $items = $jsonOrArray;
    }
    foreach ($items as $it) {
        if ($it === '' || $it === null)
            continue;
        echo '<li>' . htmlspecialchars($it) . '</li>';
    }
}

if (!$position) {
    echo '<main class="main"><section class="section"><div class="container" style="padding:80px 0;"><h2 style="color:#fff">Position not found</h2><p style="color:#aaa">The job you are looking for is not available or has been removed.</p><p style="margin-top:20px;"><a href="join.php" style="color:#5658BE;text-decoration:underline;">Back to Open Positions</a></p></div></section></main>';
    include 'partials/footer.php';
    exit;
}
?>
<main class="main">
    <section class="section banner-mode">


        <div class="box-content-banner">

            <div class="container-fluid">
                <div class="row mb-4" data-aos="fade-up">
                    <div class="col-md-10 ">
                        <p class="head pb-45"><?= htmlspecialchars($position['name']) ?> </p>
                        <p class="act pb-45"><?= htmlspecialchars($position['description']) ?></p>

                    </div>
                </div>

                <!-- 🔥 EXPANDABLE VIDEO SECTION STARTS -->
                <?php if (!empty($position['image'])): ?>
                    <img src="<?= htmlspecialchars($position['image']) ?>" alt="" width="1786" height="760">
                <?php endif; ?>
            </div>
        </div>

    </section>


    <section class="section">
        <div class="pb-100 pt-100 bg-900">
            <div class="container" data-aos="fade-left">

                <div class="row">
                    <div class="col-md-10">
                        <p class="role pb-45">About the Role</p>

                        <p class="job2 pb-50"><?= htmlspecialchars($position['roles']) ?></p>


                        <br><br>
                        <p class="role pb-45">What You'll do</p>
                        <div class="bullet-card">

                            <ul class="bullet-list ">
                                <?php renderListItems($position['what_you_do'] ?? '[]'); ?>
                            </ul>
                        </div>
                        <div class="pb-50 pt-50">
                            <img src="assets/imgs/page/join/line.png" alt="">
                        </div>

                        <p class="role pt-100 pb-50">Who You Are</p>
                        <div class="bullet-card">

                            <ul class="bullet-list">
                                <?php renderListItems($position['who_you_are'] ?? '[]'); ?>
                            </ul>
                        </div>
                        <div class="pb-50 pt-50">
                            <img src="assets/imgs/page/join/line.png" alt="">
                        </div>
                        <p class="role pt-100 pb-50">What We Offer</p>
                        <div class="bullet-card">

                            <ul class="bullet-list">
                                <?php renderListItems($position['what_we_offer'] ?? '[]'); ?>
                            </ul>
                        </div>


                        <p class="job2 pt-20 pb-20"><?= nl2br(htmlspecialchars($position['extras'])) ?></p>

                        <svg xmlns="http://www.w3.org/2000/svg" width="1920" height="2" viewBox="0 0 1920 2"
                            fill="none">
                            <path d="M0 1H1920" stroke="#AAAAAA" stroke-width="0.5"></path>
                        </svg>

                        <p class="role pt-100 pb-50">Interested?</p>
                        <p class="job2 ">Send your CV, portfolio, or a short video showcasing your editing
                            skills to <span class="bold">jobs@cybertronlabs.com</span>  </p>

                    </div>
                </div>


            </div>


        </div>

    </section>


    <section class="section">
        <div class="box-why-us bg-900">
            <div class="container-fluid">
                <div class="pb-45" data-aos="fade-right">
                    <p class="head">Open <span class="purple">Positions </span> <br>
                    </p>




                </div>
                <div class="row">
                    <?php
                    require_once __DIR__ . '/backend/function/functions.php';

                    $currentId = null;
                    if (isset($job['id'])) {
                        $currentId = (int) $job['id'];
                    } elseif (isset($_GET['id'])) {
                        $currentId = (int) $_GET['id'];
                    }

                    $relatedPositions = [];
                    $allActive = getActiveJobPositions($connection);
                    foreach ($allActive as $pos) {
                        if ($currentId !== null && (int) $pos['id'] === $currentId) {
                            continue; // skip current job
                        }
                        $relatedPositions[] = $pos;
                        if (count($relatedPositions) >= 4) {
                            break; // only need 4
                        }
                    }

                    // Render up to 4 other positions
                    if (!empty($relatedPositions)):
                        foreach ($relatedPositions as $rp):
                            ?>
                            <div class="col-sm-12 pb-50" data-aos="fade-right">
                                <p class="job1 "><?= htmlspecialchars($rp['name']) ?></p>
                                <p class="job2 pb-20 pt-20"><?= nl2br(htmlspecialchars($rp['description'])) ?></p>
                                <br>
                                <svg xmlns="http://www.w3.org/2000/svg" width="1920" height="2" viewBox="0 0 1920 2"
                                    fill="none">
                                    <path d="M0 1H1920" stroke="#AAAAAA" stroke-width="0.5" />
                                </svg>
                            </div>
                            <?php
                        endforeach;
                    else:
                        ?>
                        <div class="col-sm-12 pb-50" data-aos="fade-right">
                            <p class="job2 pb-20 pt-20">No more open positions right now.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>



        </div>

        </div>
    </section>




    <?php include 'partials/get.php'; ?>

</main>
<!-- Footer Start -->






<?php include 'partials/footer.php'; ?>