import urllib2
from bs4 import BeautifulSoup

import zlib

# f=urllib2.urlopen(url)

quote_page = 'http://www.emmacloth.com/Tassel-Trim-Dolphin-Hem-Striped-Tee-Dress-p-356028-cat-1727.html'
page = urllib2.urlopen(quote_page)
decompressed_data=zlib.decompress(page.read(), 16+zlib.MAX_WBITS)
#print decompressed_data

soup = BeautifulSoup(page, 'html.parser')
print soup.decode('gzip')

json_string = soup.find_all('script')
print "The json string  = ", json_string
