//Gruntfile
module.exports = function(grunt) {

    //Initializing the configuration object
    grunt.initConfig({

        // get version
        pkg: grunt.file.readJSON('package.json'),

        // Task configuration
        concat: {
            options: {
                separator: ';',
                sourceMap: true
            },
            js_core: {
                src: [
                    './assets/ahvla/javascript/**/*.js'
                ],
                dest: './public/assets/javascripts/main-<%= pkg.version %>.js'
            },
            js_plugins: {
                src: [
                    './assets/bower/blueimp-md5/js/md5.min.js',
                    './assets/bower/numeric-input/numericInput.min.js',
                    './assets/bower/pickadate/lib/picker.js',
                    './assets/bower/pickadate/lib/picker.date.js'
                ],
                dest: './public/assets/javascripts/plugins-<%= pkg.version %>.js',
                nonull: true
            }
        },
        compass: {
            dev: {
                options: {
                    cssDir: 'public/assets/stylesheets',
                    sassDir: 'assets/ahvla/scss',
                    imagesDir: 'public/assets/images',
                    importPath: [
                        'assets/bower/govuk_elements/public/sass',
                        'assets/bower/govuk_elements/govuk/public/sass'
                    ],
                    environment: 'production',
                    outputStyle: 'nested',
                    sourcemap: true
                }
            }
        },
        cssmin: {
            target: {
                files: [{
                    expand: true,
                    cwd: 'public/assets/stylesheets',
                    src: ['*.css', '!*.min.css'],
                    dest: 'public/assets/stylesheets',
                    ext: '-<%= pkg.version %>.css'
                }]
            }
        },

        // Copies templates and assets from external modules and dirs
        copy: {
            main: {
                files: [{
                    // All images from govuk elements
                    expand: true,
                    src: '**',
                    cwd: './assets/bower/govuk_elements/govuk/public/images/',
                    dest: './public/assets/images/'
                },
                {
                    // Move external-links up one level
                    expand: true,
                    src: '**',
                    cwd: './assets/bower/govuk_elements/govuk/public/images/icons/external-links',
                    dest: './public/assets/images/external-links/'
                },
                {
                    // Footer images
                    expand: true,
                    src: '**',
                    cwd: './assets/bower/govuk_template/source/assets/stylesheets/images/',
                    dest: './public/assets/stylesheets/images/'
                },
                {
                    // App images
                    expand: true,
                    src: '**',
                    cwd: './assets/ahvla/images/',
                    dest: './public/assets/images/'
                },
                {
                    // JS - GOV.UK elements
                    expand: true,
                    src: '**',
                    cwd: './assets/bower/govuk_elements/govuk/public/javascripts/',
                    dest: './public/assets/javascripts/'     
                },
                {
                    // JS - jQuery
                    src: './assets/bower/jquery/dist/jquery.min.js',
                    dest: './public/assets/javascripts/jquery.min.js'
                },
                {
                    // JS - GOV.UK elements - public application
                    src: './assets/bower/govuk_elements/public/javascripts/application.js',
                    dest: './public/assets/javascripts/application.js'
                },
                {
                    // CSS
                    expand: true,
                    src: '**',
                    cwd: 'assets/ahvla/css/',
                    dest: 'public/assets/stylesheets/' 
                },
                {
                    // Fonts
                    expand: true,
                    src: '**',
                    cwd: 'assets/ahvla/fonts/',
                    dest: 'public/assets/fonts/'
                }] 
            }
        },

        watch:{
            scripts:{
                files: './assets/ahvla/javascript/**/*.js',
                tasks: 'concat:js_core',
                //options: {
                    //livereload: {
                        //port: 9005,
                        //key: grunt.file.read('./config_dev/ahvla.key'),
                        //cert: grunt.file.read('./config_dev/ahvla.crt')
                    //}
                //}
            },
            compass: {
                files: ['**/*.{scss,sass}'],
                tasks: ['compass:dev','notify:watch'],
                //options: {
                    //livereload: {
                        //port: 9005,
                        //key: grunt.file.read('./config_dev/ahvla.key'),
                        //cert: grunt.file.read('./config_dev/ahvla.crt')
                    //}
                //}
            },
            app: {
                files: [
                    "./app/views/**/*.php"
                ],
                //options: {
                    //livereload: {
                        //port: 9005,
                        //key: grunt.file.read('./config_dev/ahvla.key'),
                        //cert: grunt.file.read('./config_dev/ahvla.crt')
                    //}
                //}
            }
        },

        notify: {
            watch: {
                options: {
                    title: 'Task Complete',  // optional 
                    message: 'SASS is done compiling', //required 
                }
            }
        }

    });

    // Plugin loading
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-phpunit');
    grunt.loadNpmTasks('grunt-notify');

    // Development Build
    grunt.registerTask('default', [
        'concat',
        'copy:main',
        'compass',
        'cssmin'
    ]);

    // Production Build
    grunt.registerTask('prod', [
        'concat',
        'copy:main',
        'compass',
        'cssmin'
    ]);
};
