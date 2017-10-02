# masscan-solr
```
git clone https://github.com/39ff/masscan-solr
php ./src/apache-solr/importJson.php
```

## Solr Duplication Detection
check it out here https://wiki.apache.org/solr/Deduplication

Example:
```
<updateRequestProcessorChain name="dedupe">
    <processor class="org.apache.solr.update.processor.SignatureUpdateProcessorFactory">
      <bool name="enabled">true</bool>
      <bool name="overwriteDupes">false</bool>
      <str name="signatureField">id</str>
      <str name="fields">ip,timestamp</str>
      <str name="signatureClass">org.apache.solr.update.processor.Lookup3Signature</str>
    </processor>
    <processor class="solr.LogUpdateProcessorFactory" />
    <processor class="solr.RunUpdateProcessorFactory" />
  </updateRequestProcessorChain>
  ```
  
Now you should request like this when solr-update
  ```
  /update?commit=true&update.chain=dedupe
  ```
