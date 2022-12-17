const pages = {};
const base_url ="";

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

  pages.postAPI = async (api_url, api_data, api_token = null) => {
    try {
      return await axios.post(api_url, api_data, {
        headers: {
          Authorization: "token " + api_token,
        },
      });
    } catch (error) {
  pages.Console("Error from Post API", error);
    }
  };


  pages.getAPI = async (api_url) => {
    try {
      return await axios(api_url);
    } catch (error) {
  pages.Console("Error from Linking (GET)", error);
    }
  };