ROOT=$(pwd)

rm -fr $ROOT/repo

mkdir $ROOT/repo
cd $ROOT/repo
git init
echo "FILE1" > FILE1
git add FILE1
git commit -m "CREATE FILE1"
git branch parallel
echo "FILE2" > FILE2
git add FILE2
echo "FILE1+" > FILE1
git add FILE1
git commit -m "MODIFY FILE1 AND CREATE FILE2"
git checkout parallel
echo "FILE3" > FILE3
git add FILE3
git commit -m "CREATE FILE3"
git checkout master
git merge parallel -m "MERGE BRANCH"
mkdir FOLDER
echo "FILE4" > FOLDER/FILE4
git add FOLDER/FILE4
git commit -m "CREATE FOLDER/FILE4"
git rm FILE3
git commit -m "DELETE FILE3"
