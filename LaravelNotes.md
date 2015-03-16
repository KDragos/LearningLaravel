## Laravel Notes from Laracasts ##

### Video 4: Passing Data to Views ###
To make a controller in command line:
	php artisan make:controller [ControllerName]

Views don't go to the db or fetch any data. 

Ways to go to pages from controllers WITH DATA: 

1. To return information that's just one variable:
	return view('pages.about')->with("name", $name);

	to print the data out on the next page: {{ $name }}
	to do unescaped data: {!! $name !!} 
	When displaying information from form inputs, run the escaped section.

2. To return an array of information:
	return view('pages.about')->with([
		(key 	=> value)
		"first"	=> "Kristin",
		"last"	=> "Dragos",
		"age"	=> 31
		]);
	The keys become the variable names. It's what you'd use on the next page. 

3. You can also use the php compact() function. It'll take in keys, and try to find variables that have that same name.
	return view('pages.about', compact("first", "last"));

### Video 5: Blade 101 ###

Using Blade we can create templates of pages. 
The app.blade.php page is the layout page by default. 

@yield("content")  -> This can be in a container, etc. 
To call it lager @extends('app') page.
@section('content')
	Inside this would be what actually shows up on the page inside that yield section.
@stop

Can have multiple @yield sections. Can be good for having some JavaScript run on only certain pages. 

if statements
	@if ($first == "John")
		run some code
	@else 
		run some code.

	@unless --> like and (if !) condition.
	@foreach
	@forelse --> if you have some content for some but not for others.

Example: 
	@foreach ($people as $person)
		<li> $person.name <li>
	@endforeach

$people could be an empty array or collection... 
	
	@if (count($people))
		<ul>
		@foreach ($people as $person)
			<li> {{ $person }} </li>
		@endforeach
		</ul>
	@endif


### Video 6: Environments and Configuration ###

.env file has keys and values.
	DB config file. --> has access for sqlite, mysql, pgsql, etc. 
		You'll have to change the db if you want to change the default.
.env files are always ignored by git.


### Video 7: Migrations ###

	version control for DB.
	like creating a class to help setup the database. 
	Help to keep db consistant for many devs working on the same page.
	Database needs to exist already in order to run the migration file.
		"php artisan migrate;"
	This command will create or update the tables.
	The migrations table never needs to be touched. Laravel 5 will use that to keep track of it.

	php artisan migrate:rollback - like your undo. You can fix it and rerun it. 

	php artisan make:migration [create_<<name>>_table] --create="<<tableName>>"
		This command will make a migration file for the table, and create the table in the database. 
		Theh table will have an auto incrimenting id, and some timestamps for created at and updated at.

	php artisan make:migration describe_what_you_are_doing_here
		This creates a migration file that's less specific.

	php artisan make:migration describe_what_you_are_doing_here --table="tableName"

	$table->text('excerpt');
		This one will ensure that the column has a value.

	$table->text('excerpt')->nullable();
		This means that the column can be null.

### Eloquent 101 ###

	Active record implementation = Eloquent

	make:model = make an eloquent model class.

	One class would represent one row for a table. Ex. A user model for a user table. An article model for an article table.
	Eloquent models have things that find, saves, and updates a record in our db. See model

	$article = new App\Article

	the eloquent model class is in this folder.
	vendor/laravel/framework/src/Illuminate/Database/Eloquent