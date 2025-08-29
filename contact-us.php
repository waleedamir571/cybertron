<?php include 'partials/header.php'; ?>
<main class="main">
    <section class="section banner-mode">


        <div class="box-content-banner">

            <div class="container-fluid">
                <div class="row mb-4" data-aos="fade-up">
                    <div class="col-md-7">
                        <p class="head pb-45">Let's <span class="purple">Discuss </span>
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
                        <p class="number fw-400">Let's talk about your project. Contact us today and our team will get
                            back to you shortly.</p>


                    </div>
                </div>
            </div>

            <br><br>
            <div class="box-services bg-0 box-projects">
                <div class="container-fluid">
                    <form action="backend/action/action.php" method="POST" id="contactForm" enctype="multipart/form-data">
                        <input type="hidden" name="type" value="contactForm">
                        <input type="hidden" name="page" value="Contact Us">
                        <div class="row align-items-baseline">
                            <div class="col-md-6  aos-init" data-aos="fade-left">

                                <div class="form-group">
                                    <label for="full_name" class="label">Full Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name"
                                        aria-describedby="nameHelp" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone_no">Phone Number </label>
                                    <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label for="region">Region </label>
                                    <input type="text" class="form-control" id="region" name="region" placeholder="">
                                </div>



                            </div>
                            <div class="col-md-6  aos-init" data-aos="fade-left">

                                <div class="form-group">
                                    <label for="email" class="label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        aria-describedby="emailHelp" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label for="company_name">Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="budget" class="label">Budget</label>
                                    <input type="text" class="form-control" id="budget" name="budget"
                                        aria-describedby="budgetHelp" placeholder="">
                                </div>

                            </div>
                            <div class="col-md-12  aos-init" data-aos="fade-left">

                                <div class="form-group">
                                    <label for="services" class="label">Services</label>
                                    <select class="form-control" id="services" name="services[]">
                                        <option value="Web Development">Web Development</option>
                                        <option value="Mobile App Development">Mobile App Development</option>
                                        <option value="UI/UX Design">UI/UX Design</option>
                                        <option value="DevOps">DevOps</option>
                                        <option value="Custom Software Development">Custom Software Development</option>
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-12  aos-init" data-aos="fade-left">

                                <div class="form-group">
                                    <label for="project_details" class="label">Project Details</label>
                                    <textarea class="form-control" id="project_details" name="project_details" rows="6" placeholder="Message" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6  aos-init" data-aos="fade-left">

                                    <div class="form-group">
                                        <label for="project_document" class="label">Upload Project Document</label>
                                        <div class="upload-box" onclick="document.getElementById('project_document').click()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="43" height="43"
                                                viewBox="0 0 43 43" fill="none">
                                                <path d="M16.1479 30.3764V19.9367L12.668 23.4166" stroke="#5658BE"
                                                    stroke-width="1.68382" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M16.1475 19.9367L19.6274 23.4166" stroke="#5658BE"
                                                    stroke-width="1.68382" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M38.7668 18.1968V26.8965C38.7668 35.5963 35.2869 39.0762 26.5871 39.0762H16.1474C7.44768 39.0762 3.96777 35.5963 3.96777 26.8965V16.4568C3.96777 7.75706 7.44768 4.27716 16.1474 4.27716H24.8472"
                                                    stroke="#5658BE" stroke-width="1.68382" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M38.7663 18.1967H31.8065C26.5866 18.1967 24.8467 16.4568 24.8467 11.2369V4.27713L38.7663 18.1967Z"
                                                    stroke="#5658BE" stroke-width="1.68382" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <p class="uploa pb-10 pt-10">Upload Document</p>
                                            <p class="doc">Upload your document in PDF, DOC, or DOCX format.</p>
                                        </div>

                                        <input type="file" id="project_document" name="project_document" 
                                               accept=".pdf, .doc, .docx" style="display: none;"
                                               onchange="displayFileName('project_document', 'doc-filename')">
                                        <span id="doc-filename" class="file-name"></span>
                                    </div>
                                </div>
                                <div class="col-md-6  aos-init" data-aos="fade-left">

                                    <div class="form-group">
                                        <label for="project_video" class="label">Upload Project Video</label>
                                        <div class="upload-box" onclick="document.getElementById('project_video').click()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="43" height="43"
                                                viewBox="0 0 43 43" fill="none">
                                                <path d="M16.1479 30.3764V19.9367L12.668 23.4166" stroke="#5658BE"
                                                    stroke-width="1.68382" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M16.1475 19.9367L19.6274 23.4166" stroke="#5658BE"
                                                    stroke-width="1.68382" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M38.7668 18.1968V26.8965C38.7668 35.5963 35.2869 39.0762 26.5871 39.0762H16.1474C7.44768 39.0762 3.96777 35.5963 3.96777 26.8965V16.4568C3.96777 7.75706 7.44768 4.27716 16.1474 4.27716H24.8472"
                                                    stroke="#5658BE" stroke-width="1.68382" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M38.7663 18.1967H31.8065C26.5866 18.1967 24.8467 16.4568 24.8467 11.2369V4.27713L38.7663 18.1967Z"
                                                    stroke="#5658BE" stroke-width="1.68382" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <p class="uploa pb-10 pt-10">Upload Video</p>
                                            <p class="doc">Upload your video in MP4, MOV, or AVI format.</p>
                                        </div>

                                        <input type="file" id="project_video" name="project_video" 
                                               accept=".mp4, .mov, .avi, .wmv" style="display: none;"
                                               onchange="displayFileName('project_video', 'video-filename')">
                                        <span id="video-filename" class="file-name"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12  aos-init" data-aos="fade-left">
                                <button type="submit" class="btn btn-default grow-up w-100">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        function displayFileName(inputId, spanId) {
            const input = document.getElementById(inputId);
            const span = document.getElementById(spanId);
            if (input.files.length > 0) {
                span.textContent = input.files[0].name;
                span.style.display = 'block';
                span.style.color = '#5658BE';
                span.style.marginTop = '5px';
            }
        }
    </script>

  <?php include 'partials/logos.php'; ?>


    <?php include 'partials/get.php'; ?>
</main>

<?php include 'partials/footer.php'; ?>