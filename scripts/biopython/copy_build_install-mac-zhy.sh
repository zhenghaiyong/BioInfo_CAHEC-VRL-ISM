cp ~/workspace/GitHub/zhenghaiyong/BioInfo_CAHEC-VRL-ISM/scripts/biopython/BioSQL/Loader.py /Users/flyzhy/Desktop/CAHEC/zhy/biopython-1.62b/BioSQL/
python setup.py build
rm -rf ./install/*
python setup.py install --home=./install
sudo rm -rf /opt/local/Library/Frameworks/Python.framework/Versions/2.7/lib/python2.7/site-packages/Bio
sudo rm -rf /opt/local/Library/Frameworks/Python.framework/Versions/2.7/lib/python2.7/site-packages/BioSQL
sudo rm -rf /opt/local/Library/Frameworks/Python.framework/Versions/2.7/lib/python2.7/site-packages/biopython-1.62b-py2.7.egg-info
sudo cp -rf ./install/lib/python/{Bio,BioSQL,biopython-1.62b-py2.7.egg-info} /opt/local/Library/Frameworks/Python.framework/Versions/2.7/lib/python2.7/site-packages/
