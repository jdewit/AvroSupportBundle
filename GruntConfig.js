module.exports = function(grunt) {
  var name = 'wardell-support';
  var bundlePath = 'vendor/avro/support-bundle/Avro/SupportBundle';

  return {
    tasks: {
      jshint: {
        options: {
          node: true,
          globals: {
            jQuery: true,
            it: true,
            expect: true,
            browser: true,
            beforeEach: true,
            angular: true,
            inject: true,
            describe: true,
            module: true
          }
        },
        support: {
          options: {
          },
          files: {
            src: ['Gruntfile.js', 'Resources/private/js/ng/**/*.js']
          }
        }
      },
      less: {
          support: {
            files: {
              "Resources/public/css/wardell-support.css": ['Resources/assets/less/**/*.less']
            }
          }
      },
      ngtemplates:  {
          support:      {
              src:      'Resources/ng/src/*/views/**/*.html',
              dest:     'Resources/ng/dist/core.tpl.js',
              options: {
                  url: function(url) { return url.replace('Resources/ng/src/', ''); }
              }
          }
      },
      rsync: {
        support: {
          options: {
            exclude: ['.git'],
            src: __dirname + '/',
            dest: '<%= params.project_dir %>/'+ bundlePath
          }
        }
      },
      watch: {
          support: {
              files: [__dirname + '/**/*'],
              tasks: [],
          },
          //support_less: {
              //files: [__dirname + '/Resources/assets/less/**/*.less'],
              //tasks: ['less:support'],
          //}
          //js: {
              //files: [__dirname + '/Resources/ng/js/**/*.js'],
              //tasks: ['jshint', 'uglify'],
              //options: {
                  //livereload: true
              //}
          //},
          //ng_views: {
              //files: [__dirname + '/Resources/ng/**/*.html'],
              //tasks: ['ngtemplates'],
              //options: {
                  //livereload: true
              //}
          //}
      },
      uglify: {
          options: {
              mangle: false,
              compress: true,
              compile: true,
              beautify: {
                  beautify: false
              }
          }
      }
    }
  };
};
