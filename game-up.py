import shutil
import os



"""
	Author: Kim Clarence Penaflor
	Sept. 26, 2016
	Small python script to upload games
"""

game_name = input()

#make directories:

#make resources.game directory:
os.mkdir("resources/views/games/"+game_name)

#make a directory for the game
os.mkdir("public/games/"+game_name)

#make public.css directory
os.mkdir("public/games/"+game_name+"/css")

#make public.js directory
os.mkdir("public/games/"+game_name+"/js")

#move game index to views folder
shutil.move("public/games_uploads/"+game_name+"/index.php","resources/views/games/"+game_name)

#move game css folder to public.css folder
shutil.move("public/games_uploads/"+game_name+"/games/"+game_name+"/css","public/games/"+game_name)

#move game js folder to public.js folder
shutil.move("public/games_uploads/"+game_name+"/games/"+game_name+"/js","public/games/"+game_name)


