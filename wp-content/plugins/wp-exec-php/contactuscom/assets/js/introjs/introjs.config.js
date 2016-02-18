//ON READY DOM LOADED
cUsCF_myjq(document).ready(function($) {

    $('.ug').click(function(){
        startIntroB();
    });

    //SHOW INTROJS HINTS
    $('.introjs-skipbutton').on('click',function(){

        bootbox.confirm("User guide will appear again using the help button above.", function(result) {
            if(result){
                $.ajax({ type: "POST", url: ajax_object.ajax_url, data: {action:'cUsCF_disable_introjs'},
                    success: function(data) {},
                    async: true
                });
            }
        });

    });


});


function startIntro(){
    var intro = introJs();
    intro.setOptions({
        steps: [
            {
                //1
                intro: "<div class='intro_box_ai'><a href='" + home_url + "' class='welcome-message' target='_blank'></a></div>"
            },
            {
                //2
                element: '#cu_nav_forms',
                intro: "<div class='intro_box_ai intro'><h1>Forms Panel</h1><ul><li>Shows a list of forms in your ContactUs.com account.</li><li>Shows customization options for each form as well as form previews. </li><li>Allows you to place the callout tab of a specific form, across your entire site. (You can do this with 1 or more forms).  </li><li>Provides one-click navigation to configure your ContactUs.com forms.</li></ul></div>"
            },
            {
                //3
                element: '#cu_nav_page',
                intro: "<div class='intro_box_ai intro'><h1>“Form/Page” Panel</h1><ul><li>Shows a list of your WP pages, onto which you can add a ContactUs.com form.</li><li>Allows you to choose one form for each page. </li><li>You can set the form to be displayed within the page (Inline), and/or to be displayed as a callout tab that triggers that form.</li></ul></div>",
                position:'bottom'
            },
            {
                //4
                element: '#cu_nav_docu',
                intro: "<div class='intro_box_ai intro'><h1>Documentation</h1><p>Takes you to our documentation library, to a list of articles related to this plugin. </p></div>",
                position: 'bottom'
            },
            {
                //5
                element: '#cu_nav_contacts',
                intro: "<div class='intro_box_ai intro'><h1>Contacts</h1><p>This button takes you to the Contact Manager in your ContactUs.com Admin panel. There you can:</p><ul><li>Edit lead status.</li><li>Add lead information and  notes. </li><li>Track your lead's activity in your website.</li><li>Send and keep track of email conversations.</li></ul></div>",
                position: 'bottom'
            },
            {
                //6
                element: '#cu_nav_create-form',
                intro: "<div class='intro_box_ai intro'><h1>Create a New Form</h1><ul><li>Choose what form you want to create.</li><li>Allows you to choose one form for each page. </li><li>Remember to reload the plugin settings page after you save and publish your form.</li><li>This action will give the plugin a chance to update the forms list.</li></ul></div>",
                position: "bottom"
            },
            {
                //7
                element: '.help_section',
                intro: "<div class='intro_box_ai intro'><h1>Need Help?</h1><p>If you get lost, here you can request support or start the tour again!</p></div>",
                position: "left"
            }
        ]
    });

    intro.start();
}

function startIntroB(){
    var intro = introJs();
    intro.setOptions({
        steps: [

            {
                //2
                element: '#cu_nav_forms',
                intro: "<div class='intro_box_ai intro'><h1>Forms Panel</h1><ul><li>Shows a list of forms in your ContactUs.com account.</li><li>Shows customization options for each form as well as form previews. </li><li>Allows you to place the callout tab of a specific form, across your entire site. (You can do this with 1 or more forms).  </li><li>Provides one-click navigation to configure your ContactUs.com forms.</li></ul></div>"
            },
            {
                //3
                element: '#cu_nav_page',
                intro: "<div class='intro_box_ai intro'><h1>“Form/Page” Panel</h1><ul><li>Shows a list of your WP pages, onto which you can add a ContactUs.com form.</li><li>Allows you to choose one form for each page. </li><li>You can set the form to be displayed within the page (Inline), and/or to be displayed as a callout tab that triggers that form.</li></ul></div>",
                position:'bottom'
            },
            {
                //4
                element: '#cu_nav_docu',
                intro: "<div class='intro_box_ai intro'><h1>Documentation</h1><p>Takes you to our documentation library, to a list of articles related to this plugin. </p></div>",
                position: 'bottom'
            },
            {
                //5
                element: '#cu_nav_contacts',
                intro: "<div class='intro_box_ai intro'><h1>Contacts</h1><p>This button takes you to the Contact Manager in your ContactUs.com Admin panel. There you can:</p><ul><li>Edit lead status.</li><li>Add lead information and  notes. </li><li>Track your lead's activity in your website.</li><li>Send and keep track of email conversations.</li></ul></div>",
                position: 'bottom'
            },
            {
                //6
                element: '#cu_nav_create-form',
                intro: "<div class='intro_box_ai intro'><h1>Create a New Form</h1><ul><li>Choose what form you want to create.</li><li>Allows you to choose one form for each page. </li><li>Remember to reload the plugin settings page after you save and publish your form.</li><li>This action will give the plugin a chance to update the forms list.</li></ul></div>",
                position: "bottom"
            },
            {
                //7
                element: '.help_section',
                intro: "<div class='intro_box_ai intro'><h1>Need Help</h1><p>If you get lost, here you can request support or start the tour again!</p></div>",
                position: "left"
            }
        ]
    });

    intro.start();
}