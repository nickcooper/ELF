require 'escape'
require 'etc'
require 'open3'
require 'capistrano/ext/multistage'
require 'term/ansicolor'
require 'json'
require 'tmpdir'

# Wrapper for term-ansicolor
# Usage:
#     >>> puts Color.red, 'DANGER DANGER', Color.reset
#     >>> puts Color.yellow, 'WARNING WARNING', Color.reset
class Color
    extend Term::ANSIColor
end

module OS extend self
    def is_linux?
        RUBY_PLATFORM =~ /linux/i
    end
    def is_osx?
        RUBY_PLATFORM =~ /darwin/i
    end
    def is_windows?
        RUBY_PLATFORM =~ /mswin/i
    end
end

load 'deploy'
load 'config/deploy'
load 'config/repo'
load 'config/dev'
