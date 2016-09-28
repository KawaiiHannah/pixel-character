module.exports = function( grunt ) {

	// Load all tasks
	require( 'load-grunt-tasks' )( grunt );

	// Show elapsed time
	require( 'time-grunt' )( grunt );

	grunt.initConfig({

		pkg: grunt.file.readJSON( 'package.json' ),

		sass: {
			dev: {
				files: [{
					expand: true,
			        cwd: 'assets/scss',
			        src: ['*.scss'],
			        dest: 'assets/css',
			        ext: '.css'
				}],
				options: {
					style: 'extended',
					precision: 7,
					sourceMap: false,
					sourceMapEmbed: false
				}
			},
		},
		cmq: {
		    options: {
		      log: false
		    },
		    your_target: {
		      files: {
		        'assets/css/': [ 'assets/css/*.css' ]
		      }
		    }
		},
		postcss: {
			options: {
				map: false,
				processors: [
					require('autoprefixer-core')({
						browsers: ['last 2 versions', 'ie 9', 'ie 10', 'android 4.3', 'android 4.4', 'firefox 34', 'firefox 35', 'opera 27', 'opera 26']
					}),
					require('cssnano')({

						'discardDuplicates': true,
						'discardEmpty': true,
						'core': true,
						'mergeRules': true,

					}) // minify the result
				]
			},
			dev: {
				options: {
					map: false,
				},
				src: ['assets/css/style.css']
			},
		},
		json_to_sass: {
			dev: {
			    files: [
			    	{
			    		src: [
			    			'data/base-maker-data.json'
			    		],
			    		dest: 'assets/scss/inc/_base-maker-data-vars.scss'
			    	}
			    ]
			},
		},
		watch: {
			sass: {
				files: [
					'*.scss',
					'**/*.scss'
				],
				tasks: ['sass:dev', 'postcss:dev']
			},
		},
		browserSync: {
		    dev: {
		        bsFiles: {

		            src : [
                        'assets/css/*.css',
                        '*.php',
                        '**/*.php',
                    ],

		        },
		        options: {
		            proxy: "10kb-character.localhost",
		            watchTask: true,
		        }
		    }
		},
	});

	// Register tasks
	grunt.registerTask('default', [
		'dev'
	]);
	grunt.registerTask('dev', [
		'json_to_sass',
		'sass:dev',
		'postcss:dev',
		'cmq',
		'browserSync',
		'watch',
	]);
};