'use strict';

const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));

gulp.task('default', function () {
    return gulp.src('sass/neon.scss')
    .pipe(sass())
    .pipe(gulp.dest('.'))
});