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

### Video 8: Eloquent 101 ###

	Active record implementation = Eloquent

	make:model = make an eloquent model class.

	One class would represent one row for a table. Ex. A user model for a user table. An article model for an article table.
	Eloquent models have things that find, saves, and updates a record in our db. See model

	$article = new App\Article

	the eloquent model class is in this folder.
	vendor/laravel/framework/src/Illuminate/Database/Eloquent

	At first, when you get the article back, you'll get some crazy long number/letter combo. That's because the object is empty. When you add stuff to it, it won't be just that. 
	
	To add/set/update stuff: "$article->title = {title}", "$article->body = {body text}", 
	After updating be sure to save it. $article->save();

	For timestamps, Laravel uses something called Carbon. 
		$article->published_at = Carbon\Carbon::now();

	To see the object: 
	$article   or $article->toArray();

	To save it to the database:
		$article->save();

	To see everything we have in that table. 
	App\Article::all()->toArray(); 

	To search for something ("where id = 1")
	$article = App\Article::find(1);
	
	To search for something ("where body = "lorem ipsum"")
	$article = App\Article::where('body', 'lorem ipsum')->get();
		Returns a collection. -> kind of like "arrays on steroids"
	
	To search for the very first record where it matches.
		$article = App\Article::where('body', 'lorem ipsum')->first();
			Returns one record. 

	A "short cut." To create and save at the same time.
	This is called "MassAssignment"
	$article = App\Article::create(['title' => 'new article', 'body' => 'new body', 'published_at' => Carbon\Carbon::now()]);
		create will fill the fields, and persist/save it.
	Be aware! This could cause vulnerability (sql injections).
		Laravel will try to protect you. You need to specifiy which things can  be mass assigned.


	To specify mass assigned things...
		1. Go into the model.
		2. Create a protected $fillable = [] property.
		3. example: protected $fillable = [
						'title',
						'body',
						'published_at'
					]
		4. If it's not included in the array, it can't be mass assigned.

	To update something in the db. 
		$article->update(['body' => 'updated again!']);
		This is similar to the create method. It will call the method, fill the properties and save it.


	Create migration
	create task table.
	open file
	set fields
	migrate database
	make model
		php make model task
	create/update/fetch records. 

### Video 9: Basic Model/View/Controller Workflow ###
