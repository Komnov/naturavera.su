(function( $ ) {
    'use strict';

    $(document).ready(function(){
        var main_tour = new Shepherd.Tour();

        main_tour.options.defaults = {
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour shadow-md bg-purple-dark',
            showCancelLink: true,
            useModalOverlay: true,
            scrollTo: true,
            tetherOptions: {
                constraints: [
                    {
                        to: 'scrollParent',
                        attachment: 'together',
                        pin: false
                    }
                ]
            }
        };

        var next_button_text = 'Next';
        var back_button_text = 'Previous';

        //Start Sences guide tour

        main_tour.addStep('tour_title', {
            title: 'Set A Title To Tour Virtual Tour',
            text: "Give a name to your virtual tour.",
            attachTo: '#post-body-content bottom',
            buttons: [
                {
                    classes: 'udp-tour-end',
                    text: "End Tour",
                    action: main_tour.cancel
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next,
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        })
        main_tour.addStep('scene_section', {
            title: 'Add Scene ID ',
            text: "Set the Scene ID for your first panorama image. The Scene ID has to be unique for each scene and without any spaces or special characters. You can set it as Scene1, S1, or simply 01.",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '.single-scene.rex-pano-tab.active .sceneid right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });

        main_tour.addStep('upload_image', {
            title: 'Upload Panorama Image',
            text: "Click on the Upload button to upload your panorama image.",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '.single-scene.rex-pano-tab.active .scene-upload right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });

        $('.single-scene.rex-pano-tab.active .scene-upload').on('click',function(event){
            main_tour.hide()
            if($(this).parent().find('.wpvr_continue_guide').length == 0 && !main_tour.canceled ){
                $(this).parent().append('<span class="wpvr_continue_guide" >Continue to guide</span>');
            }
        })
        $(document).on('click', "span.wpvr_continue_guide", function() {
            $(this).remove();
            main_tour.show("preview_tour_button")
            $('body').addClass('shepherd-active')
        });




        main_tour.addStep('preview_tour_button', {
            title: 'Preview The Image in Tour Mode',
            text: "Click on the Preview button to view your uploaded panorama in virtual tour mode.",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#panolenspreview left',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('preview_tour_section', {
            title: 'Your Image In Tour Mode',
            text: "Here is a preview of your panorama image in tour mode. You can control it and mode around to see it in 360 degree view.",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#wpvr_item_builder__box left',

            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('publish_tour', {
            title: 'Save Your Tour',
            text: "Click on this Publish button to save this as a tour. You can always find it in your tour list.",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#publishing-action left',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('sence_end', {
            title: 'Publish Your Tour',
            text: "Now your tour is ready to be published on your website.\n" +
                "\n" +
                "To learn how to publish it on your website,<a href='https://rextheme.com/docs/wp-vr-wpvr-shortcode-embed-virtual-tour/' target='_blank'> follow this detailed documentation.\n</a>" +
                "\n" +
                " To continue customizing this tour, click on Next.",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '.rex-pano-tabs',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: "Start Customizing",
                    // action: main_tour.next
                    action: function() {
                        main_tour.next()
                        $(".rex-pano-nav-menu.main-nav ul li.hotspot span").trigger('click')
                    }
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });

        // End scenes Tour Guide

        // Start Hotspot
        main_tour.addStep('hotspot_start', {
            title: 'Let\'s add a Hotspot',
            text: "Add a Hotspot inside your tour to show additional information like Paragraph, Heading, Image, Video, or multiple content.",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#wpvr-main-nav .hotspot right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                        $(".rex-pano-nav-menu.main-nav ul li.scene span").trigger('click')
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('hotspot_id', {
            title: 'Set Hotspot ID',
            text: "Set an unique Hotspot ID to each of your Hotspots. Avoid Spaces & special characters. Set it like H1 or 01",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#scene-1-hotspot-1 .hotspot-setting  right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    // action: main_tour.next
                    action: function() {
                       var post_ID = $("#post_ID").val();
                        $(".pnlm-ui.pnlm-grab").trigger('click');
                        // console.log($(".pnlm-ui.pnlm-grab").trigger('click'));
                        main_tour.next()

                    }
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('choose_previwer', {
            title: 'Choose The Spot',
            text: "In this Preview, drag to your desired location and click on it, exactly where you want to set the hotspot",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#wpvr_item_builder__box left',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('assigin_pitch_yaw', {
            title: 'Assign Pitch & Yaw',
            text: "Once you see the Pitch & Yaw value for the spot, click on this Arrow. It'll be set as the coordinate for your hotspot",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#panodata  right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('pitch_yaw_set', {
            title: 'Pitch & Yaw is Set',
            text: "Here you can see the Pitch & Yaw has been set for the hotspot.",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#scene-1-hotspot-1 .hotspot-pitch  right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('pitch_yaw_set_2', {
            title: 'Pitch & Yaw is Set',
            text: "Here you can see the Pitch & Yaw has been set for the hotspot.",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#scene-1-hotspot-1 .hotspot-yaw  right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('on_click_content_info', {
            title: 'Set The Content for Click Action',
            text: "Here, you can set what content your viewer will see after clicking on the Hotspot. You can either set an URL or any other content using this editor. To learn more, follow this guide to set content using editor",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#scene-1-hotspot-1 .hotspot-type.hotspot-setting:not(.hotspot-hover)  top',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('on_hover_info', {
            title: 'Set The Content for Click Action',
            text: "Here, you can set what content your viewer will see after clicking on the Hotspot. You can either set an URL or any other content using this editor. To learn more, follow this guide to set content using editor",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#scene-1-hotspot-1 .hotspot-hover  top',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('preview_on_hotspot', {
            title: 'Preview Your Hotspot Content',
            text: "Click on Preview to see how the content is appearing for the hotspot.",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#panolenspreview  right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('save_process_hotspot', {
            title: 'Save Your Progress',
            text: "Click on the Update button to save your work. And just like that, you can create an unlimited number of virtual tours and add your customization to them.\n",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#publish  left',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: 'Done',
                    action: main_tour.complete
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        //End Hotspot
        /**
         * Scroll to Popup
         *
         * @param {Object} step
         */
        var scroll_to_popup = function(step) {
            main_tour.going_somewhere = false;
            if (!step) {
                step = main_tour.getCurrentStep();
            }
            var popup = $(step.el);
            var target = $(step.tether.target);
            $('body, html').animate({
                scrollTop: popup.offset().top - 50
            }, 500, function() {
                window.scrollTo(0, popup.offset().top - 50);
            });

        }
        main_tour.start();
        main_tour.on('cancel', cancel_tour);

        /**
         * Cancel tour
         */
        function cancel_tour() {
            // The tour is either finished or [x] was clicked
            main_tour.canceled = true;
           var get_param =  getParameterByName("wpvr-guide-tour");
           if(get_param == "1"){
               var newUrl = updateParam("wpvr-guide-tour",0);
               if (window.history != 'undefined' && window.history.pushState != 'undefined') {
                   window.history.pushState({ path: newUrl }, '', newUrl);
               }
           }
        };

        /**
         * Get URL parameter By name
         */
        function getParameterByName(name, url = window.location.href) {
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }
        /**
         * Delete parameter By name
         */
        function updateParam (name,value, url = window.location.href){
            var url = new URL(url);
            var search_params = url.searchParams;
            search_params.delete(name);

            url.search = search_params.toString();

            var new_url = url.toString();

            return new_url;
        }

    })

})( jQuery );