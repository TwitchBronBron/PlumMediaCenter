(function() {
    'use strict';
    var gulp = require('gulp'),
            uglify = require('gulp-uglify'),
            concat = require('gulp-concat'),
            sourcemaps = require('gulp-sourcemaps'),
            livereload = require('gulp-livereload');

    gulp.task('scripts', function() {
        return gulp.src(['app/app.js', 'app/**/*.js', '!app/app.min.js'])
                .pipe(sourcemaps.init())
                .pipe(concat('app.min.js'))
                .pipe(uglify())
                .pipe(sourcemaps.write('./'))
                .pipe(gulp.dest('app'));
    });

    gulp.task('watch', ['default'], function() {
        var server = livereload();
        gulp.watch(['index.html', 'assets/**/*.*', 'app/**/*.*', '!app/app.min.js'], ['default']);


        gulp.watch(['app/**/*.*']).on('change', function(file) {
            server.changed(file.path);
        });
    });

    gulp.task('default', ['scripts']);
}());