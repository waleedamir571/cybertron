<?php include 'partials/header.php'; ?>
<main class="main">
    <section class="section banner-mode">


        <div class="box-content-banner">

            <div class="container-fluid">
                <div class="row mb-4" data-aos="fade-up">
                    <div class="col-md-7">
                        <p class="head pb-45">Let’s <span class="purple">Discuss </span>
                            your <span class="bold">Project</span> </p>
                        <p class="act pb-45">We are committed to understanding your requirements and crafting a tailored
                            solution that aligns with your goals.</p>
                        <p class="act pb-45">Enter your details and someone from our team will reach out to find a time
                            to connect with you.</p>

                    </div>
                </div>



            </div>

        </div>

        <!-- 🔽 NEXT SECTION STARTS -->

    </section>


    <section class="section is-mode ">
        <div class="box-services bg-0 box-projects">
            <div class="container-fluid">
                <div class="row align-items-baseline">

                    <div class="col-md-12  aos-init aos-animate" data-aos="fade-left">
                        <p class="contact">Contact Us</p>
                        <p class="number fw-400">Let’s talk about your project. Contact us today and our team will get
                            back to you shortly.</p>


                    </div>
                </div>
            </div>

            <br><br>
            <div class="box-services bg-0 box-projects">
                <div class="container-fluid">
                    <div class="row align-items-baseline">
                        <div class="col-md-6  aos-init" data-aos="fade-left">

                            <div class="form-group">
                                <label for="exampleInputEmail1" class="label">Full Name</label>
                                <input type="email" class="form-control" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" placeholder="">

                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Phone Number </label>
                                <input type="number" class="form-control" id="exampleInputPassword1" placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Region </label>
                                <input type="text" class="form-control" id="exampleInputPassword1" placeholder="">
                            </div>



                        </div>
                        <div class="col-md-6  aos-init" data-aos="fade-left">

                            <div class="form-group">
                                <label for="exampleInputEmail1" class="label">Email Address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" placeholder="">

                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Company Name </label>
                                <input type="number" class="form-control" id="exampleInputPassword1" placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="label">Budget</label>
                                <input type="text" class="form-control" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" placeholder="">

                            </div>

                        </div>
                        <div class="col-md-12  aos-init" data-aos="fade-left">

                            <div class="form-group">
                                <label for="exampleInputEmail1" class="label">Email Address</label>
                                <select class="form-control" id="exampleFormControlSelect1">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>

                            </div>


                        </div>
                        <div class="col-md-12  aos-init" data-aos="fade-left">

                            <div class="form-group">
                                <label for="exampleInputEmail1" class="label">Project Details</label>
                                <textarea class="form-control" rows="6" placeholder="Message"></textarea>

                            </div>
                        </div>
                        <div class="row">
                        <div class="col-md-6  aos-init" data-aos="fade-left">

                            <div class="form-group">
                                <label for="exampleInputEmail1" class="label">Project Details</label>
                                <div class="upload-box" onclick="document.getElementById('fileInput').click()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="43" height="43" viewBox="0 0 43 43"
                                        fill="none">
                                        <path d="M16.1479 30.3764V19.9367L12.668 23.4166" stroke="#5658BE"
                                            stroke-width="1.68382" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M16.1475 19.9367L19.6274 23.4166" stroke="#5658BE"
                                            stroke-width="1.68382" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M38.7668 18.1968V26.8965C38.7668 35.5963 35.2869 39.0762 26.5871 39.0762H16.1474C7.44768 39.0762 3.96777 35.5963 3.96777 26.8965V16.4568C3.96777 7.75706 7.44768 4.27716 16.1474 4.27716H24.8472"
                                            stroke="#5658BE" stroke-width="1.68382" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M38.7663 18.1967H31.8065C26.5866 18.1967 24.8467 16.4568 24.8467 11.2369V4.27713L38.7663 18.1967Z"
                                            stroke="#5658BE" stroke-width="1.68382" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    <p class="uploa pb-10 pt-10">Upload Doc</h3>
                                    <p class="doc">Upload your document in PDF or DOC format.</p>
                                </div>

                                <input type="file" id="fileInput" accept=".pdf, .doc, .docx"
                                    onchange="uploadFile(this.files[0])">

                            </div>
                        </div>
                         <div class="col-md-6  aos-init" data-aos="fade-left">

                            <div class="form-group">
                               <label for="exampleInputEmail1" class="label">Project Details</label>
                                <div class="upload-box" onclick="document.getElementById('fileInput').click()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="43" height="43" viewBox="0 0 43 43"
                                        fill="none">
                                        <path d="M16.1479 30.3764V19.9367L12.668 23.4166" stroke="#5658BE"
                                            stroke-width="1.68382" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M16.1475 19.9367L19.6274 23.4166" stroke="#5658BE"
                                            stroke-width="1.68382" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M38.7668 18.1968V26.8965C38.7668 35.5963 35.2869 39.0762 26.5871 39.0762H16.1474C7.44768 39.0762 3.96777 35.5963 3.96777 26.8965V16.4568C3.96777 7.75706 7.44768 4.27716 16.1474 4.27716H24.8472"
                                            stroke="#5658BE" stroke-width="1.68382" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M38.7663 18.1967H31.8065C26.5866 18.1967 24.8467 16.4568 24.8467 11.2369V4.27713L38.7663 18.1967Z"
                                            stroke="#5658BE" stroke-width="1.68382" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    <p class="uploa pb-10 pt-10">Upload Doc</h3>
                                    <p class="doc">Upload your document in PDF or DOC format.</p>
                                </div>

                                <input type="file" id="fileInput" accept=".pdf, .doc, .docx"
                                    onchange="uploadFile(this.files[0])">

                            </div>
                        </div>
                        </div>

                        <div class="col-md-12  aos-init" data-aos="fade-left">

                            <a class="btn btn-default grow-up w-100" href="#"
                                >Submit</a>
                        </div>

                    </div>
                </div>

            </div>


        </div>
    </section>


  <?php include 'partials/logos.php'; ?>


    <?php include 'partials/get.php'; ?>

</main>
<!-- Footer Start -->






<?php include 'partials/footer.php'; ?>