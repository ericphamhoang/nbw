module.exports = (grunt)->
  grunt.initConfig
    watch:
      stylus_stylesheets: # Watching stylus file, compiling all .styl to .css
        files: ['wp-content/plugins/nbw/styles/**/*.styl']
        tasks: ['stylus']
      coffeescripts: # Watching stylus file, compiling all .styl to .css
        files: ['wp-content/plugins/nbw/js/**/*.coffee']
        tasks: ['coffee']
      livereloads:
        files: ['*.css','*.html','*.js']
        options:
          livereload:
            port: 9001
    stylus:
      compile:
        options:
          paths: ['./']
          compress: true
        files:
          'wp-content/plugins/nbw/styles/main.css': 'wp-content/plugins/nbw/styles/main.styl'
          'wp-content/plugins/nbw/styles/admin_option.css': 'wp-content/plugins/nbw/styles/admin_option.styl'
    coffee:
      compile:
        options:
          paths: ['./']
          compress: true
        files:
          'wp-content/plugins/nbw/js/main.js': 'wp-content/plugins/nbw/js/main.coffee'
          'wp-content/plugins/nbw/js/admin_option.js': 'wp-content/plugins/nbw/js/admin_option.coffee'
    exec:
      ## If SCP is available, put your public key on the host and use
      upload_css: { cmd: 'scp wp-content/plugins/nbw/styles/main.css ssh_username@ssh_host.com:public_html/wp-content/plugins/nbw/styles/main.css' }
      upload_js: { cmd: 'scp wp-content/plugins/nbw/js/main.js ssh_username@ssh_host.com:public_html/wp-content/plugins/nbw/styles/main.js' }

      ## If SFTP is available, put your public key on the host and use
      # upload_css: { cmd: '(echo "put wp-content/plugins/nbw/styles/main.css public_html/wp-content/plugins/nbw/styles/main.css"; echo quit)|sftp -b - sftp_username@sftp_host' }
      # upload_js: { cmd: '(echo "put wp-content/plugins/nbw/js/main.js public_html/wp-content/plugins/nbw/js/main.js"; echo quit)|sftp -b - sftp_username@sftp_host' }

      ## If FTP is the only option,
      # Step 1. Download ncftp at http://www.ncftp.com/download/ and install on your machine or `brew install ncftp` on Mac
      # Step 2. Create a file named ftp_configuration.cfg with the following content

      # host hostname.com
      # user ftp_username
      # password ftp_password

      # Step 3. Put ftp_configuration.cfg into .gitignore if you haven't done so
      # Use the following commands:
      # upload_css: { cmd: "ncftpput -f ftp_configuration.cfg public_html/wp-content/plugins/nbw/styles/ wp-content/plugins/nbw/styles/main.css" }
      # upload_js: { cmd: "ncftpput -f ftp_configuration.cfg public_html/wp-content/plugins/nbw/js/ wp-content/plugins/nbw/js/main.js" }


  grunt.loadNpmTasks('grunt-contrib-watch')
  grunt.loadNpmTasks('grunt-contrib-stylus')
  grunt.loadNpmTasks('grunt-contrib-coffee')
  grunt.loadNpmTasks('grunt-exec')
  grunt.registerTask 'default', ['watch']