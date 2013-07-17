(function($, document, window, undefined){
	
	var defaults = {
		title: "", // Text title to show above the list
		source: "", // Source URL to get the data from
		datamap: {}, // Callback allowing remapping of the retrived data
		template: "#widtemplate", // The script template to use for the items
		showmore: true, // Display a "show more" link to reveal all available items
		cacheEnabled: (window.localStorage !== undefined), // Enable or disable the cache
		cachePrefix: "", // In case you need to refresh the cache based on an option change
		cacheTimeout: 300 // Seconds before the cache expires
	};

	function JSWidget(element, options){
		this.element = element;
		this.options = $.extend( {}, defaults, options );
		this.init();
	}

	$.fn.JSWidget = function(o){
		return this.each(function(){
			new JSWidget(this, o);
		});
	}

	JSWidget.prototype = {
		init : function(){
			this.items = [];			
			this.moreEle = null;
			this.dfd = $.Deferred();
			this.initHTML();
			this.fetchItems();
			this.renderItems(this.options.count);
		},

		initHTML : function(){
			if(this.options.title.length > 0){
				$(this.element).before(this.options.title);
			}
			$(this.element).addClass('loading').html("");
		},

		fetchItems : function(){
			var jswidget = this;
			var cached = jswidget.getCachedData();
			if( cached ){
				jswidget.items = cached;
				jswidget.dfd.resolve();
			}
			else{
				$.ajax({
					url: jswidget.options.source,
					dataType: "json"
				})
				.error(function(){
					jswidget.genericError();
				})
				.success(function(data){
					if(!data){
						console.log(data)
						jswidget.genericError();
					} else {
						jswidget.items = jswidget.options.datamap(data);
						jswidget.cacheData(jswidget.items);
						jswidget.dfd.resolve();
					}
				});
			}
		},

		// Get data from the cache if it's available and less than x minutes old
		getCachedData : function(){
			var now = new Date();
			var	cache;
			var	cacheTime;

			if( this.options.cacheEnabled ){
				if( localStorage.getItem(this.options.cachePrefix + this.options.template) ){
					cache = $.parseJSON( localStorage.getItem(this.options.cachePrefix + this.options.template) );
					cacheTime = new Date(cache.date);

					// Is the cache is older than x minutes
					if( cacheTime < now.setSeconds( now.getSeconds() - this.cacheTimeout ) ){
						localStorage.removeItem(this.options.cachePrefix + this.options.template);
						return false;
					} else {
						return cache.data;
					}

				} else {
					return false;
				}
			}
			else {
				localStorage.removeItem(this.options.cachePrefix + this.options.template);
				return false;
			}
		},

		cacheData : function(data){
			if( !this.options.cacheEnabled ) return false;
			var toCache = { "date" : new Date().getTime(), "data" : data };
			localStorage.setItem(this.options.cachePrefix + this.options.template, JSON.stringify( toCache ) );
		},

		renderItems : function(){
			var jswidget = this;
			var theList = $(jswidget.element);
			var listItems;
			this.dfd.done(function(){
				
				$(jswidget.element).removeClass('loading');

				listItems = jswidget.items.slice(0, jswidget.options.count);
				
				theList.hide().html( jswidget.compileTemplate(listItems) ).fadeIn(200);

				if(jswidget.options.showmore){
					jswidget.enableMore();
				}
				
			});
		},

		compileTemplate: function(data){
			var template = $(this.options.template).html(),
				i,
				len = data.length,
				html = "",
				pattern;
			for(i=0; i<len; i++){
				var temp = template;
				for(x in data[i]){
					pattern = new RegExp("{{" + x + "}}", "g");
					temp = temp.replace(pattern, data[i][x]);
				}
				html += temp;
			}
			return html;
		},

		enableMore : function(){
			var jswidget = this;
			if(!this.moreEle){
				this.moreEle = $('<p><a href="#" class="more">More</a></p>').insertAfter(this.element)
				.click(function(){
					jswidget.loadMore();
					return false;
				});
			}
		},

		loadMore : function(){
			var time = 20, jswidget = this;
			var listItems = jswidget.items.slice(jswidget.count, jswidget.items.length);
			
			// Sequentially append and fade in each item
			$(jswidget.compileTemplate(listItems)).each(function(i, item){
				setTimeout(function(){
					$(item).hide().appendTo(jswidget.element).show();
				},time*i);
			});

			// Hide the 'show more' button
			$( jswidget.moreEle ).slideUp(300);			
		},

		genericError : function(){
			$(this.element).removeClass('loading').hide().html( "<li>Nothing to see here...</li>" ).fadeIn(200);
		}
	}

})(jQuery, document, window, undefined);