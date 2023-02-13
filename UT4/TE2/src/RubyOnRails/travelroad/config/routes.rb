Rails.application.routes.draw do
#  get 'places/index'
  # Define your application routes per the DSL in https://guides.rubyonrails.org/routing.html

  # Defines the root path route ("/")
  # root "articles#index"
  root "places#index"

 get "/", to: "places#index"
 get "/visited", to: "places#visited"
 get "/wished", to: "places#wished"
end
