import shutil
import os



"""
	Author: Kim Clarence Penaflor
	Sept. 26, 2016
	Small python script to upload games
"""

game_name = input()

#make game directories
os.mkdir("public/games/"+game_name)
os.mkdir("resources/views/games/"+game_name)

#move game index to views folder
shutil.move("public/game_uploads/"+game_name+"/index.php","resources/views/games/"+game_name)

#move game css folder to public.css folder
shutil.move("public/game_uploads/"+game_name+"/games/"+game_name+"/css","public/games/"+game_name)

#move game js folder to public.js folder
shutil.move("public/game_uploads/"+game_name+"/games/"+game_name+"/js","public/games/"+game_name)

#remove the remaning files
os.rmdir("public/game_uploads"/+game_name)

print("DONE!")