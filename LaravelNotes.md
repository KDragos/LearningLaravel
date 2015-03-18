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

	1. register routes and controller/method to be called.
			can use an annonymous function in place of the controller and method.
	2. create controller.
		php artisan make:controller ArticleController
	3. Add method to the controller. 
		At the top of the page, import the namespace: 
			use App\Article;
		Create the function.
			public function index() {
				$articles= \App\Article::all();

				return $articles;
				(This returns json! But it just dumps json out on the page. We'd need to return a view instead.)

				return view('article.index', compact('articles'));
			} 
	4. Resources/views - Create view.
		Create html to display the data.

		@extends('app')
		@section('content')
			<h1>Articles</h1>
			@foreach ($articles as $article)
				<article>
					<h2> <a href="">{ $article->title }}</a></h2>
					<div class="body"> {{ $article-body }} </div>
				</article>
			@endforeach
		@stop

		in the routes page:
		Route::get('articles/{id}', 'ArticlesController@index');
		in the controller, set up the method. 
			public function show ($id) {
				return $id;   <--- We'd want to set up a regex to designate specifically what we'd want to catch.
				$article = Article::find($id);  <--- doesn't account for null values.
				return $article;
				
				To account for null values... We could: 
				if (is_null($article)) {
					abort(404);  <--- Remember to turn off the errors ("debug => false" in config/app)
				}

				Instead let Laravel help us:
				$article = Article::findOrFail($id);


				return view('articles.show', compact('article'));
			}
					Realistically we'd want something more specific to the entity than a column from the db. (ex. articles/my-first-article)

	5. Set the links to go to the appropriate place. 
		We could hard code it (the uri). 
			<h2> <a href="/articles/{{ $article->id }}">{ $article->title }}</a></h2>
			
		Instead... We can think in terms of model/controllers
			<h2> <a href="{{ action('ArticlesController@show', [$article->id]) }}">{ $article->title }}</a></h2>

		We could also do 
			<h2> <a href="{{ url('/articles', $article->id }}">{ $article->title }}</a></h2>
		
		Can do named routes as well. (addressed in another video.)
	
### Video 10: Form ###

	In routes: 
		Route::get('articles/create', 'ArticlesController@create')
		More specific routes should come before routes with wild cards. 
		Ex: line 246 should come before ('articles/{id}')

	In controller: 
		public function create() {
			return view('articles.create');
		}
	
	Create view:
		@extends('app')
		@section('content')
			<h1>Write a new article</h1>

			<form action="">

			</form>
		@stop

	Rather than create the form manually we can include a package.
		in terminal: composer require illuminate/html
			See in github: github.com/illuminate/html
			Has a html builder and a form builder
			Laravel Facade... we'll see in future videos.

	Tell Laravel we've pulled in illuminate. 
		HTML Service provider class. Registers objects, etc. 
		use Illuminate\Support\ServiceProvider;

		See config\app.php 
			1. Scroll down to providers section. 
				Add to the bottom: "Illuminate\HtmlServiceProvider",

			2. Scroll down to aliases
				Add to bottom: "'Form' => 'Illuminate\Html\FormFacade'"
				Add to bottom: "'Html' => 'Illuminate\Html\HtmlFacade'"
	
	Once we have the above step completed... 
		{{!! Form::open( url(articles) <-- You can use url(), a named route, or an ['action'] like previous video. --> ) !!}}
			<div class="form-group">
				{{!! Form::label('name', 'Name:') !!}}
				{{!! Form::text('name', null, ['class' => 'form-control']) !!}} <-- This uses bootstrap.
						name of element, default, [additional perameters]	
			</div>
			<div class='form-group'>
				{{!! Form::label('body', 'Body:') !!}}
				{{!! Form::textarea('body', null, ['class' => 'form-control']) !!}}
			</div>
			<div class="formgroup">
				{{!! Form:submit('Add Article', ['class' => 'btn btn-primary form-control']) !!}}
															(bootstrap classes)
			</div>

		{{!! Form::close() !!}}
		This will fill in an action, method and token!
			Our form has no validation, yet!


		Route::post('articles', 'ArticlesController@store');

		public function store() {
			stores it in db and redirects.
			we'll use a facade to get the variables (Use Request; at top.)
			$input = Request::all();
				$article = new Article;
				$article->title = $input['title'];
				eloquent protects against sql injections so this is okay.

				This one won't save it to the db just yet. 
				$article = new Article(['title' => 'article']);

				or this:
				Article::create($input); <-- Mass assignment variables will be filled. Nothing else. It'll discard everything else. 
				$input['published_at'] = Carbon::now();
				return redirect('articles');

			$input = Request::get('body'); <--- to get specific field.
		}

		public function index() {
			$articles = Article::lastest()->get();
			opposite:			::oldest()

				or
			$articles = Article::order_by('published_at', 'desc')->get();

		}

### Video 11: Dates, Mutators, and Scopes

	Carbon::now() <-- only for time. Not for date.

	in our form: 
	<div class="form-group">
		{{!! Form::label('published_at', 'Publish On:') !!}}
		{{!! Form::input('date', 'published_at', null, ['class' => 'form-control']) !!}}
						type of input, column name, default, 
						('date', 'published_at', date('Y-m-d'), ['class' => 'form-control'])
	</div>

	mutators - give us a way to manipulate data before its inserted into the db. 
	in Article model:
	
	protected $dates = ['published_at']; <--- This sets it as a carbon instance. We can then use the carbon methods on it. 
	   (naming format: set column name attribute)
	public function setPublishedAtAttribute($date) {
		$this->attributes['published_at'] = Carbon::createFormFormat('Y-m-d', $dtate);
	}

	To ensure you're only seeing records that have been published. 
		ArticlesController
		public function index() {
			$articles = Article::latest('published_at')->where('published_at', '<=', Carbon::now())->get();
			return view('articles.index', compact('articles'));
		}

		or: 
			public function index() {
				$articles = Article::latest('published_at')->published()->get();
				return view('articles.index', compact('articles'));
			}

			published() becomes a scope. Scopes are functions that could essentially be used anywhere.
			We'd go to our article model, to create the function there. That way we can used the published() function anywhere we'd need it. 
			public function scopePublished($query) {
				$query->where('published_at', '<=', Carbon::now());
			}

			public function scopeUnpublished($query) {
				$query->where('published_at', '>', Carbon::now());
			}

			can get $article->created_at->addDays(8)->
			->diffForHumans()); Show a "this will be 5 days from now."