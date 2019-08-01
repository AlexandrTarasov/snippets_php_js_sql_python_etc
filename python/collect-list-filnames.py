#!/usr/bin/python
import os, sys

# Open a file
path = "."

files = os.listdir( path )
type(files)

mytxt = filter(lambda x: x.endswith('.jpg'), files) #only text files



for d in mytxt:
    print (d)

