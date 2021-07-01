@servers(['localhost' => '127.0.0.1'])

@task('prepare-dist', ['on' => 'localhost'])
pwd
@endtask


@story('deploy')
prepare-dist
@endstory
