![YumTown](YumTown.svg.png)

# YumTown
SoftwareDev Project
by Andrew Krall, Alex Barnes, Stuti Chugh, John Jolley

## December 10, 2017
## Description
* YumTown is an web application that can be used to search recipes by dish or ingredient name. Upon searching it returns upto 12 recipes cards 
that includes the image, name, and time it would take to make each recipe. You can double-click on one of the recipe cards to view details of
that recipe including ingredients, instructions, and a pricebreakdown. You can create an account and login.
* We used spoonacular API to get recipes and the price breakdown widget. Initially we started by using the yummly API but it didn't have a lot of functionalities that we wanted to achieve for our web app. This included returning detail recipe steps and price breakdown based on ingredient. Yummly, big oven, and many other APIs we initially worked required us to have an ID for each ingredient and then do the mmath based on quantity needed. Spoonacular has a great widget that can be accessed by a simple API ajax POST request that includes a table and chart view that tells us the price breakdown.
* We mostly accomplished everything we started out wanting for the app. Price Breakdown was one of the most challenging parts as it was hard to pass inregedients corresponding to each recipe into a pre-built widget. The CSS to make it fit into our page was also challenging but we were able to accomplish that. 
* One thing we weren't able to accomplish was having the user being able to save recipes and view their saved recipes. If we had more time or were to continue with this project we would have tried to accomplish finishing that feature.
* The programming languages and technologies used for this project are javascript, PHP, HTML, jquery/ajax, mustache templates, and spoonacular API

![Usecase Diagram](usecasediagram.pdf)

![Usecase Discription](Use-case-description.pdf)

![Architecture Diagram](architecture_diagram.pdf)

![Flow Diagram](SEProjectFlowDiagram.pdf)

![Entity Relationship Diagram](ERD_SWEngineering_Revised_Final.pdf)

![Axosoft Link](https://akrall.axosoft.com/)

![Project Video](https://www.youtube.com/watch?v=ShkZPDCm4Zk)
