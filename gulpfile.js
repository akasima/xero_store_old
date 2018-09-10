var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var fileinclude = require('gulp-file-include');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixerOptions = {
  browsers: ['last 3 versions', '> 5%', 'Firefox ESR']
};
var spritesmith = require('gulp.spritesmith');

gulp.task('watch', function() {
  gulp.watch(['./assets/scss/*.scss', './assets/scss/**/*.scss'], ['sass']);
  gulp.watch(['./markup/include/*.html', './markup/html/**/*.html'], ['fileinclude']);
  gulp.watch(['./assets/sprite/*.png'], ['sprite', 'sass']);
});

gulp.task('sass', function() {
  return gulp.src(['./assets/scss/*.scss', './assets/scss/**/*.scss'])
    .pipe(sourcemaps.init())
    .pipe($.sass({ outputStyle: 'expanded' }).on('error', $.sass.logError))
    .pipe(sourcemaps.write('../map'))
    .pipe(gulp.dest('./assets/css'))
});

gulp.task('fileinclude', function() {
  gulp.src(['markup/html/*/*.*'])
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file'
    }))
    .pipe(gulp.dest('./markup/result'));
});

gulp.task('sprite', function () {
  var spriteData = gulp.src('./assets/sprite/*.png').pipe(spritesmith({
    imgName: 'shop_sprite.png',
    imgPath: '../../img/shop_sprite.png',
    cssName: '_sprite.scss',
    algorithm: 'top-down',
    padding: 10
  }));

  var imgStream = spriteData.img
    .pipe(gulp.dest('./assets/img/'));

  var cssStream = spriteData.css
    .pipe(gulp.dest('./assets/scss/user'));

  return merge(imgStream, cssStream);
});
