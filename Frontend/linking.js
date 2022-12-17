const pages = {};

pages.Console = (title, values, oneValue = true) => {
    console.log("---" + title + "---");
    if (oneValue) {
      console.log(values);
    } else {
      for (let i = 0; i < values.length; i++) {
        console.log(values[i]);
      }
    }
    console.log("--/" + title + "---");
  };

  pages.loadFor = (page) => {
    eval("pages.load_" + page + "();");
  };