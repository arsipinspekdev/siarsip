from PIL import Image
import sys

def remove_white_bg(input_path, output_path, tolerance=220):
    img = Image.open(input_path).convert('RGBA')
    datas = img.getdata()

    new_data = []
    for item in datas:
        # If the pixel is mostly white (above tolerance)
        if item[0] > tolerance and item[1] > tolerance and item[2] > tolerance:
            new_data.append((255, 255, 255, 0)) # transparent
        else:
            new_data.append(item)

    img.putdata(new_data)
    img.save(output_path, 'PNG')

remove_white_bg('public/logo.png', 'public/logo.png')
remove_white_bg('public/favicon.ico', 'public/favicon.ico')
