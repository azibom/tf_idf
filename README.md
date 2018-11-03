# tf_idf
## How to implement a tf_idf algorithm 

today i implement the tf idf algorithm with php
but first what is tf idf. 
it is short for term frequency–inverse document frequency
and we use it to give weight to the words and sort them by their importance, 
if i want to say it easy first we found the words frequency then we try to find idf which is count of sentences that have one special word
now we can use the formula and find out the weight
![a](https://github.com/cmohammadc/tf_idf/blob/master/formula.png)
you can go to the core and change this part(for ignore some words)
```php
    public function filter()
    {
        $filter = array();

        // Prepositions and Conjunctions and etc
        $filter[] = array(
            'بین',
            'و',
            'از',
            'برای',
            'تا',
            'اما',
            'هر',
            'است',
            'شد',
            'گشت',
            'گردید',
        );

        return $this->mergeArrays($filter);
    }

```
and it is test with tree persian sentences :sunglasses:


![a](https://github.com/cmohammadc/tf_idf/blob/master/test.png)

