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
  case "contact-us.php";
    $title_name = "Contact Us | Donald’s Book Publishing – Get in Touch Today";
    $description = "Have questions or need support? Contact Donald’s Book Publishing for inquiries about publishing services, book design, printing, or general assistance.";
    $keywords = "contact Donald’s Book Publishing, book publishing contact USA, publishing support USA, get in touch with publisher, author services contact, USA book publishing help ";

    break;
  case "disclaimer-policy.php";
    $title_name = "Disclaimer Policy | Donald’s Book Publishing – Legal Notice & Liability";
    $description = "Read the Disclaimer Policy of Donald’s Book Publishing outlining limitations of liability, content accuracy, and user responsibility when using our website.";
    $keywords = "Donald’s Book Publishing disclaimer, website disclaimer USA, content liability notice, legal disclaimer policy, publishing website disclaimer, limitations of liability ";

    break;
  case "terms-and-conditions.php";
    $title_name = "Terms & Conditions | Donald’s Book Publishing – User Agreement";
    $description = "Review the Terms & Conditions for using Donald’s Book Publishing. Learn about user responsibilities, service terms, copyright policies, and legal guidelines.";
    $keywords = "Donald’s Book Publishing terms and conditions, user agreement, service terms USA, website terms, legal terms for publishing, content usage policy ";

    break;
  case "privacy-policy.php";
    $title_name = "Privacy Policy | Donald’s Book Publishing – Your Data, Our Responsibility";
    $description = "Read the Privacy Policy of Donald’s Book Publishing to understand how we collect, use, and protect your personal information when you use our services.";
    $keywords = " Donald’s Book Publishing privacy policy, data protection policy, how we use your data, personal information privacy, user data policy USA, website privacy practices ";

    break;
  case "web-content-writing-service.php";
    $title_name = "Web Content Writing Services in the USA | Donald’s Book Publishing";
    $description = "Get high-quality web content writing services in the USA. SEO-optimized, compelling, and conversion-driven content tailored to your business needs.";
    $keywords = "web content writing services USA, SEO content writing, website content writers USA, professional content writing, custom web copy USA, USA content creators ";

    break;
  case "blog-writing-services.php";
    $title_name = "Expert Blog Writing Services in the USA | Donald’s Book Publishing";
    $description = "Grow your brand with professional blog writing services in the USA. SEO-friendly, engaging content tailored to your industry, audience, and goals.";
    $keywords = "blog writing services USA, professional blog writers, SEO blog content USA, custom blog writing, content writing services USA, blog content creation";

    break;
  case "articles-services.php";
    $title_name = "Top Web Design Services in the USA | Donald’s Book Publishing";
    $description = "Get expert professional article writing services in the USA. Quality content tailored to boost your brand and engage your audience";
    $keywords = "article writing services USA, SEO article writers, professional content writing USA, blog writing services, custom article writing, USA content creators";

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