module.exports = function (grunt) {


    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        bump: {
            options: {
                files: ['package.json', 'composer.json'],
                commit: true,
                commitMessage: '~ bump to version %VERSION%',
                commitFiles: ['-a'],
                push: false,
                createTag: false,
                globalReplace: false
            }
        },
        gitinfo: {}


    });

    grunt.loadNpmTasks('grunt-bump');

};
