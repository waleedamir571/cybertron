<?php
error_reporting(E_ALL);

// start country block code
// Get user IP address
// $ip = $_SERVER['REMOTE_ADDR'];
// // Using the API to get information about this IP
// $details = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=$ip"));
// // City
// $city = $details->geoplugin_city;
// // Using geoplugin to get the continent for this IP
// $continent = $details->geoplugin_continentCode;
// // And for the country
// $country = $details->geoplugin_countryCode;
// // Blocked countries
// $blocked_countries = array("PK", "IN", "CN", "NGA");
// // Check if the country is in the blocked list
// if (in_array($country, $blocked_countries)) {
//     header("Location: https://hancockpublishers.com/country-block.php");
//     exit;D
// }
// end country block code

// $functions = basename($_SERVER['PHP_SELF']); 

$baseUrl = "https:/cybertronlabs.com/";
$functions = basename($_SERVER['PHP_SELF']); // Get just the filename

switch ($functions) {
  case "index.php";
    $title_name = " Software Development Company in the UAE| Cybertron Labs  ";
    $description = " Cybertron Labs delivers AI, software, and full-stack solutions to help businesses innovate, scale, and achieve digital transformation.";
    $keywords = "";


    break;
  case "web-app-development.php";
    $title_name = "Web App Development Services in the UAE| Cybertron Labs ";
    $description = "Bring your vision to life with Cybertron Labs’ web app development services. We build scalable, responsive, and intuitive web applications.";
    $keywords = "";

    break;
  case "mobile-app-development.php";
    $title_name = "Mobile App Development Services in the UAE | Cybertron Labs";
    $description = "Cybertron Labs specializes in custom mobile app development, delivering responsive and scalable applications. We transform your ideas into impactful mobile experiences. ";
    $keywords = "";
    break;

  case "custom-software-development.php";
    $title_name = "Custom Software Development Services in the UAE |Cybertron Labs";
    $description = ": Cybertron Labs offers custom software development services that streamline operations and enhance efficiency.  Partner with us to build user-centric solutions.";
    $keywords = "";

    // $form_heading1 = "Contact Us";
    // $form_heading2 = "Pitch Your Book Idea";
    // $indexfollow = "index, follow";
    // $url = $baseUrl . "services";
    // $keywordwords = "Hancock Publisher Services";
    break;
  case "design-and-development.php";
    $title_name = "Design & Development Services in the UAE|Cybertron Labs";
    $description = "Cybertron Labs offers end-to-end design and development services, including custom web and mobile applications, UI/UX design, and seamless integrations.";
    $keywords = "";

    break;
  case "maintainance-and-support.php";
    $title_name = "Maintenance & Support Services in the UAE | Cybertron Labs";
    $description = "  Cybertron Labs offers comprehensive maintenance and support services to keep your digital products running smoothly. From regular updates to troubleshooting. ";
    $keywords = " ";


    break;
  case "ecommerce.php";
    $title_name = "Expert E-Commerce Services in the UAE | Cybertron Labs";
    $description = "Cybertron Labs offers comprehensive e-commerce development services, including custom design and mobile optimization, to help your business thrive online.";
    $keywords = "";

    break;
  case "what-we-do.php";
    $title_name = "AI, DevOps & Tech Innovation Experts | Cybertron Labs";
    $description = "Discover how Cybertron Labs delivers powerful solutions in AI development, infrastructure engineering, DevOps, and custom software.";
    $keywords = "";

    break;
  case "who-we-are.php";
    $title_name = "About us- Innovators in Tech & Software | Cybertron Labs";
    $description = "Learn about Cybertron Labs: our story, mission, and team of passionate technologists. Discover how we help startups and enterprises with custom software.";
    $keywords = " ";

    break;
  case "join.php";
    $title_name = "Join our team | Careers at Cybertron Labs";
    $description = "Cybertron Labs is hiring! Whether you're into software development, 
AI or engineering, we welcome passionate talent. Discover our culture and current job openings.";
    $keywords = "";

    break;
  case "contact-us.php";
    $title_name = "Contact Cybertron Labs | Get in Touch with Our Team";
    $description = "Have questions or want to work with us? Reach out to Cybertron Labs today. We’re here to support your AI, software, and innovation needs.";
    $keywords = "  ";

    break;
  case "ui-ux-design.php";
    $title_name = "UI/UX Design Services in the UAE | Cybertron Labs";
    $description = "Cybertron Labs specializes in UI/UX design that enhances user engagement and satisfaction. Our team creates responsive and visually appealing interfaces.";
    $keywords = "";

    break;
  case "devops.php";
    $title_name = "DevOps Services in the UAE | Cybertron Labs";
    $description = " Cybertron Labs provides DevOps services that optimize your development pipeline. Improve collaboration, reduce time to market, and ensure scalable.";
    $keywords = "";

    break;
  case "staff-augmentation.php";
    $title_name = "Staff Augmentation Services in the UAE| Cybertron Labs";
    $description = "Boost your team's capabilities with Cybertron Labs' staff augmentation services. We offer skilled professionals who work alongside your team to deliver high-quality.";
    $keywords = "";

    break;
  case "web-design-and-seo.php";
    $title_name = "Top Web Design Services in the USA | Donald’s Book Publishing";
    $description = "Professional web design services in the USA tailored to your business. Get responsive, SEO-optimized websites that drive traffic, leads, and conversions";
    $keywords = "web design services USA, custom website design, responsive web design USA, professional web designers, USA web development, SEO web design services";

    break;
  case "book-design-services.php";
    $title_name = "Book Design Services in the USA | Donald’s Book Publishing";
    $description = "Get professional book design services in the USA, including custom cover design, interior formatting, and layout for self-published authors and publishers.";
    $keywords = "book design services USA, custom book cover design, interior book layout, professional book formatting, book design for self-publishing, USA book designers";

    break;
  case "book-video-trailer.php";
    $title_name = "Book Trailer Video Services in the USA | Donald’s Book Publishing";
    $description = "Engage readers with professional book trailer video services in the USA. Custom trailers for authors and publishers to market fiction, non-fiction, and more.";
    $keywords = "book trailer video services USA, book trailers for authors, custom book trailer videos, promote your book USA, book marketing videos, author video trailers";

    break;
  case "book-printing-services.php";
    $title_name = "Professional Book Printing Services in the USA | Donald’s Book Publishing";
    $description = "Affordable and high-quality book printing services in the USA. Get custom, self-publishing, or bulk book printing with fast turnaround and expert support";
    $keywords = "book printing services USA, custom book printing, self-publishing printing USA, bulk book printing, print books USA, professional book printing";

    break;
  case "audiobook-services.php";
    $title_name = "Top Audiobook Services in the USA | Donald’s Book Publishing";
    $description = "Discover the best audiobook services in the USA for unlimited streaming and downloads. Compare top platforms, features, prices, and start listening today.";
    $keywords = "audiobook services USA, best audiobook platforms, audiobook apps USA, audiobook subscriptions, top audiobook services, listen to audiobooks USA";

    break;
  case "book-editing-services.php";
    $title_name = "Professional Book Editing Services| Donald’s Book Publishing";
    $description = "Get professional Book Editing Services in USA to perfect your manuscript. Trusted by authors for clarity, flow, and publishing-ready quality";
    $keywords = "Book Editing Services, Book Editing Services in USA, Professional Book Editing Services in USA";

    break;
  case "thank-you.php";
    $title_name = "Thank-You | Donald’s Book Publishing";
    $description = "thank-you";
    $keywords = "Book Editing Services, Book Editing Services in USA, Professional Book Editing Services in USA";

    break;
  case "services.php";
    $title_name = "All Book Publishing Solutions | Donald’s Book Publishing";
    $description = "Get expert help with writing, editing, design, and publishing. 
Donald’s Book Publishing supports authors every step of the way";
    $keywords = "Donald’s Book Publishing, Book Publishing Solutions";

    break;



}





// start body google tag manager 
// $bodyGoogleTag = '

// ';
// end body google tag manager 